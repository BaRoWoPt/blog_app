<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'content' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Vui lòng nhập nội dung bình luận.',
            'content.min' => 'Nội dung bình luận phải có ít nhất :min ký tự.',
            'content.max' => 'Nội dung bình luận không được quá :max ký tự.',
        ];
    }
}
