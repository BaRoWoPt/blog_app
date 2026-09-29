<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AdminDeleteBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'min:10', 'max:255']
        ];
    }
    public function messages(): array
    {
        return [
            'reason.required' => 'Lý do xóa bài viết là bắt buộc.',
            'reason.string' => 'Lý do xóa bài viết phải là một chuỗi.',
            'reason.min' => 'Lý do xóa bài viết phải có ít nhất :min ký tự.',
            'reason.max' => 'Lý do xóa bài viết không được vượt quá :max ký tự.',
        ];
    }
}