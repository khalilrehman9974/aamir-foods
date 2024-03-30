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
            'bilty_no' => 'required',
            'deliverd_to' => 'required',
            'saleman_id' => 'required',
            'transporter_id' => 'required',

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
            'transporter_id.required' => 'Please select the Transporter',
            'saleman_id.required' => 'Please select the Saleman',
            'bilty_no.required' => 'Please Enter bilty_no',
            'deliverd_to.required' => 'Please Enter deliverd-to Information',
        ];
    }
}
