<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransporterRequest extends FormRequest
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
            'name' => 'required:max150',
            'contact_number' => 'required:max150',
            'city' => 'required:max150',
            'address' => 'required:max150'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter the name!',
            'contact_number.required' => 'Please enter the Mobile Number!',
            'city.required' => 'Please enter the city name!',
            'address.required' => 'Please enter the Address!'
        ];
    }
}
