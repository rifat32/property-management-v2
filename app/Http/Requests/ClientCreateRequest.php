<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientCreateRequest extends FormRequest
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
    public function rules()
    {
        return [

            'first_Name' => 'nullable|string|max:255',
            'last_Name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',

            'email' => 'required|string|email|max:255|unique:tenants,email',
            'phone' => 'nullable|string',
            'image' => 'nullable|string',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'country' => 'nullable|string',
            'city' => 'nullable|string',
            'postcode' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
        ];
    }
}
