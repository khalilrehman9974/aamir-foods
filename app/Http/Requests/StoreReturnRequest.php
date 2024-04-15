<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReturnRequest extends FormRequest
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
            'return_by' => 'required|max:250|string',
            'quantity' => 'required|max:20',
        ];
    }

    public function messages()
    {
        return [
            'return_by.required' => 'Return By...!',
            'quantity.required' => 'Please enter the quantity!'
        ];
    }
}
