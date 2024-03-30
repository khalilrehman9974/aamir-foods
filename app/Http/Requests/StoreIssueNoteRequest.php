<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueNoteRequest extends FormRequest
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
            'issued_no' => 'required',
            // 'issued_date' => 'required|max:150|string',
            'issued_to' => 'required|max:250|string',
            'issued_quantity' => 'required|max:20',
            'description' => 'required|string'

        ];
    }

    public function messages()
    {
        return [
            'issued_no.required' => 'Please enter the issue no!',
            // 'issued_date.required' => 'Please select the date!',
            'issued_to.required' => 'Please enter the issued to name!',
            'issued_quantity.required' => 'Please enter the quantity!',
            'description.required' => 'Please enter the description!'
        ];
    }
}
