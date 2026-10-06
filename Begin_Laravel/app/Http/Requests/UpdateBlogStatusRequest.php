<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * Chỉ admin mới được phép cập nhật trạng thái bài viết.
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
            'status' => ['required', 'in:draft,published'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Trạng thái bài viết là bắt buộc.',
            'status.in'       => 'Trạng thái bài viết phải là draft hoặc published.',
        ];
    }
}

