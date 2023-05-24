<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProfileUpdateRequest extends FormRequest
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
        $uuid = $request->uuid;
        $rules = [
                'name' => "required",
                'email' => 'required|email|unique:users,email,' . $uuid . ',uuid,deleted_at,NULL',
                'contact_number' => 'nullable|numeric|digits:10|unique:users,contact_number,' . $uuid . ',uuid,deleted_at,NULL',
                'profile_image' => 'image|mimes:jpeg,png,jpg|max:800',
            ];
        if(auth()->user()->role == config('params.company_role')){
             $rules['country_id'] = 'required';
        }
        return $rules;
    }
    public function messages()
    {
        $messages = [
                'profile_image.mimes' => 'The uploaded profile picture must be a file of type: jpg, jpeg, png.',
                'profile_image.max' => 'The uploaded profile picture must not be greater than 800 kb.',
                'profile_image.image' => 'The uploaded profile picture must be an image.',
                'contact_number.numeric' => 'The contact number must be number.',
            ];
        if(auth()->user()->role == config('params.company_role')){

            $messages['country_id.required'] = 'The country field is required.';
        }
        return $messages;
    }
}
