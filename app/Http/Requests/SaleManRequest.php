<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleManRequest extends FormRequest
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

    public function rules()
    {
        return [
            'name' => 'required:max150',
            'mobile_no' => 'required:max150',
            'address' => 'required:max150'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter the name!',
            'mobile_no.required' => 'Please enter the Mobile Number!',
            'address.required' => 'Please enter the Address!'
        ];
    }
}
