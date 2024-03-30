<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CPVStoreRequest extends FormRequest
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
            'account' => 'required',
            'cpv_no' => 'required',
            'paid_to' => 'required',
            'date' => 'required',
            'amount' => 'required',
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
            'account.required' => 'Please enter the account!',
            'cpv_no.required' => 'Please enter voucher number!',
            'paid_to.required' => 'Please enter paid to!',
            'date.required' => 'Please select the date!',
            'amount.required' => 'Please enter the amount!'
        ];
    }
}
