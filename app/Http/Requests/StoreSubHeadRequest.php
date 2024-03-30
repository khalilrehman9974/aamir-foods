<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubHeadRequest extends FormRequest
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
    public function rules(): array
    {
        return [

            'main_head' => 'required|max:250|string',
            'control_head' => 'required|max:250|string',
            'account_name' => 'required|max:250|string'
        ];
    }

    public function messages()
    {
        return [
            'main_head.required' => 'Please select the main head',
            'control-head.required' => 'Please select control head',
            'account_name.required' => 'Please enter the account name'
        ];
    }
}
