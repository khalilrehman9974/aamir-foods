<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'grn_no' => 'required',
            'date' => 'required',
            'party_id' => 'required',
            'bill_no' => 'required',

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
            'grn_no.required' => 'Please Enter GRN NO..',
            'party_id.required' => 'Please select the Party',
            'bill_no.required' => 'Please Enter Bill No',

        ];
    }
}
