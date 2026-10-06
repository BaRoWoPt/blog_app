<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Mail\TestingMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showSignupForm()
    {
        return view('auth.register');
    }

    public function signup(RegisterRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);
        Auth::login($user);
        Mail::to($user->email)->send(new TestingMail($user));
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'Đăng ký tài khoản thành công!');

    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // 1. Tạo throttle key duy nhất dựa vào email và IP người dùng
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());

        // 2. Kiểm tra nếu đã thử sai quá 5 lần
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'email' => "Bạn đã thử quá nhiều lần. Vui lòng thử lại sau {$seconds} giây.",
            ])->onlyInput('email');
        }

        // 3. Thực hiện xác thực email và password
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công:
            RateLimiter::clear($throttleKey);       // Xóa bộ đếm số lần thử
            $request->session()->regenerate();      // Làm mới Session ID

            return redirect()->intended(route('home'));
        }

        // 4. Đăng nhập thất bại:
        RateLimiter::hit($throttleKey, 60); // Tăng số lần thử sai, khóa 60 giây nếu chạm ngưỡng

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
