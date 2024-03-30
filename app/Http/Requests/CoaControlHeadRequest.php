<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoaControlHeadRequest extends FormRequest
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
            'main_code' => 'require',
            'account_name' => 'require',
        ];
    }

    public function messages()
    {
        return [
            'main_head.required' => 'Please select the main head!',
            'account_name.required' => 'Please enter the name!'
        ];
    }
}
