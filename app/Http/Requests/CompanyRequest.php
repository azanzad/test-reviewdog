<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'planid' => 'required',
            //   'contact_number' => 'nullable|min:10|max:14|unique:users,email,NULL,id,deleted_at,NULL',
            'contact.*.contact_name' => 'required',
            // 'contact.*.contact_title' => 'required',
            'contact.*.contact_email' => 'required|email',
            'website' => 'nullable|url',
            'contact.*.contact_number' => 'nullable|numeric|digits:10',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The company name field is required.',
            'email.required' => 'The company email field is required.',
            'planid.required' => 'The subscription plan field is required.',
            'contact.*.contact_name.required' => 'The contact name field is required.',
            'contact.*.contact_email.required' => 'The contact email field is required.',
            'contact.*.contact_email.email' => 'The contact email must be a valid email address.',
            'contact.*.contact_number.numeric' => 'The contact number must be number.',
        ];

    }
}