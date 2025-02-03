<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveredToPartyRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'detail_account_id' => 'required|exists:parties,id',
            'party_name' => 'required',
            'saleMan_id' => 'required|integer',
            'sector_id' => 'required|integer',
            'area_id' => 'required|integer',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'detail_account_id.required' => 'Please select the Party!',
            'detail_account_id.exists'   => 'The selected party is invalid.',
            'party_name.required' => 'Please enter the Name!',
            'saleMan_id.required' => 'Please select the SaleMan!',
            'sector_id.required' => 'Please select the Sector!',
            'area_id.required' => 'Please select the Area!',
        ];
    }
}
