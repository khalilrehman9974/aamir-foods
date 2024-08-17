<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoneRequest extends FormRequest
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
            'country_id' => 'required',
            'name' => 'required:max150'
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'Please Select the Country!',
            'name.required' => 'Please enter the name!'
        ];
    }
}
