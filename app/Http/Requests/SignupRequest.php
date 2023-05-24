<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            "name" => "required | min:3 | max:50",
            "email" => "required | email |unique:users,email",
            "contact_number" => "required | numeric | digits:10 | unique:company_contacts,contact_number",
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            "country_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'The country field is required.',
        ];

    }
}
