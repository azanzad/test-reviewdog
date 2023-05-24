<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CustomerRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'name' => 'required|max:100',
            'email' => 'required_if:customer_type,==,1|nullable|email|unique:users,email,NULL,id,deleted_at,NULL',
            'planid' => 'required_if:customer_type,==,1',
            'contact.*.contact_name' => 'required_if:customer_type,==,1',
            'contact.*.contact_title' => 'required_if:customer_type,==,1',
            'contact.*.contact_email' => 'required_if:customer_type,==,1|email|nullable',
            //'clientname' => 'required_if:customer_type,==,2|max:100',
            'companyid' => 'required_if:customer_type,==,2', //2=company_type
            'website' => 'nullable|url',
            'contact.*.contact_number' => 'nullable|numeric|digits:10',
        ];

    }
    public function messages()
    {
        return [
            'name.required' => 'The customer name field is required.',
            'email.required_if' => 'The customer email field is required.',
            'planid.required_if' => 'The subscription plan field is required.',
            'contact.*.contact_name.required_if' => 'The contact name field is required.',
            'contact.*.contact_title.required_if' => 'The contact title field is required.',
            'contact.*.contact_email.required_if' => 'The contact email field is required.',
            'contact.*.contact_email.email' => 'The contact email must be a valid email address.',
            'companyid.required_if' => 'The parent company field is required.',
            //'clientname.required_if' => 'The customer name field is required.',
            'contact.*.contact_number.numeric' => 'The contact number must be number.',
        ];

    }
}