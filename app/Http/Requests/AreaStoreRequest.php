<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'sector_id' => 'required',
            'name' => 'required:max150'
        ];
    }

    public function messages()
    {
        return [
            'sector_id.required' => 'Please Select the Sector!',
            'name.required' => 'Please enter the name!'
        ];
    }
}
