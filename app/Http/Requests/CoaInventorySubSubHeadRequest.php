<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CoaInventorySubSubHeadRequest extends FormRequest
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
            'main_head' => 'required',
            'sub_head' => 'required',
            'sub_sub_head' => 'required',
            'name' => 'required|string|max:250',
        ];
    }
}
