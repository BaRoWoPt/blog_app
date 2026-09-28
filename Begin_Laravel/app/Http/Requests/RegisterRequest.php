<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'string',
                Password::min(8)       // Ít nhất 8 ký tự
                    ->mixedCase()      // Có cả chữ thường và CHỮ HOA
                    ->numbers()        // Có ít nhất 1 chữ số
                    ->symbols(),       // Có ít nhất 1 ký tự đặc biệt (@, #, $, %,...)
                'confirmed',        // Mở ra nếu có ô nhập lại mật khẩu (password_confirmation)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không đúng định dạng.',
            'email.max' => 'Địa chỉ email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ];
    }
}
