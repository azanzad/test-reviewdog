<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardAddRequest extends FormRequest
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
        $this_year = date("y");

        return [
            'name' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The card holder name field is required.',
        ];
    }

}