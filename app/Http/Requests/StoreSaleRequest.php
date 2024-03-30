<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
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
            'dispatch_note' => 'required',
            'date' => 'required',
            'party_id' => 'required',
            'bilty_no' => 'required',
            'delivered_to' => 'required',
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
            'dispatch_note.required' => 'Please Enter Dispatch Note#',
            'party_id.required' => 'Please select the Party',
            'transporter_id.required' => 'Please select the Transporter',
            'saleman_id.required' => 'Please select the Saleman',
            'bilty_no.required' => 'Please Enter bilty_no',
            'delivered_to.required' => 'Please Enter deliverd-to Information',
        ];
    }
}
