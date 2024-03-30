<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispatchNoteStoreRequest extends FormRequest
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
            'product_id' => 'required',
            'quantity' => 'required|integer',
            'unit' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Please select the product',
            'quantity.required' => 'Please enter the quantity',
            'unit.required' => 'Please enter the unit'
        ];
    }
}
