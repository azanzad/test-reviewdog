<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => 'required|mimes:xlsx,xls',
        ];
    }
    public function messages()
    {
        return [
            'file.required' => "The file field is required",
            'file.mimes' => 'The uploaded file must be a file of type: xlsx, xls.',
        ];
    }
}