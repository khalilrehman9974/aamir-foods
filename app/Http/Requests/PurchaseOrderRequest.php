<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
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
            'name' => 'required',
            'company_name' => 'required',
            'date' => 'required',
            'address' => 'required',
            'product_id' => 'required',
            'total_quantity' => 'required',
            'price' => 'required',

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
            'name.required' => 'Please Enter the Name',
            'company_name.required' => 'Please Enter the Company Name..',
            'date.required' => 'Please select the Date',
            'address.required' => 'Please Enter the Address',
            'product_id.required' => 'Please select the Product',
            'total_quantity.required' => 'Please Enter the Total Quantity',
            'price.required' => 'Please Enter the price',
        ];
    }
}
