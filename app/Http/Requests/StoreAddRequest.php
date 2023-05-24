<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddRequest extends FormRequest
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
            'store_type' => 'required',
            'store_name' => 'required|unique:stores',
            'merchant_id' => 'required',
            'refresh_token' => 'required',
            //'marketplace_name' => 'required'
        ];

    }
    public function messages()
    {
        return [
            'store_type.required' => 'Please select at least one marketplace',
            //'marketplace_name.required' => 'Please select marketplace',
            'store_name.required' => 'Please enter store name',
            'merchant_id.required' => 'Please enter seller id',
            'refresh_token.required' => 'Refresh token is required',
        ];
    }
}