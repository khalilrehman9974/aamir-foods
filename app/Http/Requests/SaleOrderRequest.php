<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleOrderRequest extends FormRequest
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
            'date' => 'required',
            'party_id' => 'required',
            'deliverd_to' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'dzn' => 'required',
            'rate' => 'required',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'date.required' => 'Please select the date',
            'party_id.required' => 'Please select the Party',
            'deliverd_to.required' => 'Please Enter deliverd-to Information',
            'product_id.required' => 'Please Select The Product',
            'quantity.required' => 'Please Enter the Quantity',
            'dzn.required' => 'Please Enter Dzns',
            'rate.required' => 'Please Enter the Rate',
        ];
    }
}
