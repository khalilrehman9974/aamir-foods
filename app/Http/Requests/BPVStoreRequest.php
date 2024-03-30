<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BPVStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required',
            'bank_id' => 'required',
            'account_no' => 'required',
            'cheque_no' => 'required',
            'amount' => 'required|numeric',
            'paid_to' => 'required',
            'bpv_no' => 'required',
            'date' => 'required'
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'project_id.required' => 'Please select the project!',
            'bank_id.required' => 'Please select the bank!',
            'account_no.required' => 'Please select the account!',
            'cheque_no.required' => 'Please enter the cheque number!',
            'amount.required' => 'Please enter the amount!',
            'paid_to.required' => 'Please enter the paid to name!',
            'bpv_no.required' => 'Please enter the voucher number!',
            'date.required' => 'Please select the date!',
        ];
    }
}
