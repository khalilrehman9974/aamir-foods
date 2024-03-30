<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankStoreRequest extends FormRequest
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
            'name' => 'required:max70',
            'account_title' => 'required:max:40',
            'account_number' => 'required:max:40',
            'branch_name' => 'required:max:40',
            'branch_code' => 'required:max:195',
            'branch_name' => 'required:max:195',
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
            'name.required' => 'Please enter the name!',
            'account_title.required' => 'Please enter the account title!',
            'account_number.required' => 'Please enter the account number!',
            'branch_name.required' => 'Please enter the bank name!',
            'branch_code.required' => 'Please enter the branch code!',
            'branch_name.required' => 'Please enter the branch name!',
        ];
    }
}
