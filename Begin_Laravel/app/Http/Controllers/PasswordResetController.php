<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('home')->with('success', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
