<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPlanAddRequest extends FormRequest
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
            'name' => "required|unique:subcription_plans,name,NULL,id,deleted_at,NULL",
            'interval' => "required",
            'amount' => "required|numeric|min:1|max:999999",
            'interval_count' => "required|integer|min:1",
            'status' => "required",
            'plan_type' => "required",
            'annual_sales_from' => 'required',
            'annual_sales_to' => 'required_with:annual_sales_from',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => "The plan name field is required.",
            'interval.required' => "The plan duration field is required.",
            'amount.required' => "The price field is required.",
            'amount.numeric' => "The price must be a number.",
            'amount.min' => "The price must be more than 0.",
            'amount.check_stripe_amount' => "The price must be less than or equal to 999999.",
            'interval_count.required' => "The duration count field is required.",
            'interval_count.integer' => "The duration count must be a number.",
            'interval_count.min' => "The duration count must be more than 0.",
            'annual_sales_from.required' => "The annual sales field is required.",
            'annual_sales_to.required_with' => "The annual sales field is required.",
        ];

    }
}
