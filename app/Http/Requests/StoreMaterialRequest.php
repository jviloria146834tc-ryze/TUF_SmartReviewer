<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'files' => 'required_without:content|array|max:10',
            'files.*' => 'file|mimes:pdf,docx,png,jpg,jpeg|max:30720',
            'content' => 'required_without:files|nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'files.required_without' => 'Please provide either a file or paste content.',
            'content.required_without' => 'Please provide either a file or paste content.',
        ];
    }
}
