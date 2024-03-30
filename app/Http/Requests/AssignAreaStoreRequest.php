<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignAreaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'sale_mans_id' => 'required',
            'area_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'sale_mans_id.required' => 'Please Select the Sale Man!',
            'area_id.required' => 'Please Select the Area!'
        ];
    }
}
