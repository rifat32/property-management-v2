<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyCreateRequestV2 extends FormRequest
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
            'name' => "nullable|string",
            'image' => "nullable|string",
            'images' => "present|array",
            'images.*' => "string",

            'documents' => "present|array",
            'documents.*.gas_start_date' => "required|date",
            'documents.*.gas_end_date' => "required|date",
            'documents.*.description' => "nullable|date",

            'documents.*.document_type_id' => "required|numeric|exists:document_types,id",
            'documents.*.files' => "required|array",
            'documents.*.files.*' => "string",

            'address' => "nullable|string",
            'country' => "required|string",
            'city' => "required|string",
            'postcode' => "required|string",
            'town' => "nullable|string",
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'type' => "required|string",
            'reference_no' => 'required|string|max:255',
            'landlord_id' => "nullable|numeric|exists:landlords,id",
            'tenant_ids' => 'nullable|array',
            'tenant_ids.*' => 'nullable|exists:tenants,id',

            // Added fields from Software 2
            'date_of_instruction' => 'nullable|date',
            'howDetached' => 'nullable|string',
            'no_of_beds' => 'required|string',
            'no_of_baths' => 'required|string',
            'is_garden' => 'required|string',

            'propertyFloor' => 'nullable|string',
            'category' => 'required|string',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'purpose' => 'nullable|string',
            'property_door_no' => 'nullable|string',
            'property_road' => 'nullable|string',
            'county' => 'nullable|string',
        ];
    }
}
