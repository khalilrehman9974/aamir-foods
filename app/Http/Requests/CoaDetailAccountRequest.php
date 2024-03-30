<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoaDetailAccountRequest extends FormRequest
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

    public function rules(): array
    {
        return [
            'main_head' => 'required|max:250|string',
            'control_head' => 'required|max:250|string',
            'sub_head' => 'required|max:250|string',
            'sub_sub_head' => 'required|max:250|string',
            'account_name' => 'required|max:250|string'
        ];
    }

    public function messages()
    {
        return [
            'main_head.required' => 'Please select the main head',
            'control-head.required' => 'Please select control head',
            'sub-head.required' => 'Please select sub head',
            'sub-sub-head.required' => 'Please select sub-sub head',
            'account_name.required' => 'Please enter the account name'
        ];
    }
}
