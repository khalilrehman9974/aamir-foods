<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JvStoreRequest extends FormRequest
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
            'jv_no' => 'required',
            'debit_account' => 'required',
            'credit_account' => 'required',
            'debit_amount' => 'required',
            'credit_amount' => 'required',
            'date' => 'required',
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
            'jv_no.required' => 'Please enter the JVNo!',
            'debit_account.required' => 'Please enter the debited account!',
            'credit_account.required' => 'Please enter the credited account!',
            'debit_amount.required' => 'Please enter the debited amount!',
            'credit_amount.required' => 'Please enter the credited amount!',
            'date.required' => 'Please select the date!',
        ];
    }
}
