<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCardInfoRequest extends FormRequest
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
            "card_holder_name" => "required | min:3 | max:50",
        ];
    }
}
