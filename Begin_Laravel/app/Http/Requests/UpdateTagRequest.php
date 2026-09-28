<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tag_name_update' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tags', 'name')->ignore($this->route('tag')),
            ],
        ];
    }
}
