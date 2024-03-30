<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoaMainHeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'account_name' => 'required|max:150|string'
        ];
    }

    public function messages()
    {
        return [
            'account_name.required' => 'Please enter the name!'
        ];
    }
}
