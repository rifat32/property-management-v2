<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceCreateRequest extends FormRequest
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

            "logo"=>"nullable|string",
            "invoice_title"=>"required|string",
            "invoice_summary"=>"nullable|string",
            "reminder_date" => "nullable|date",
            "invoice_number" => "required|string|unique:invoices,invoice_number",
            "business_name"=>"required|string",
            "business_address"=>"required|string",



            "invoice_date"=>"required|date",
            "footer_text"=>"required|string",
            "property_id"=>"required|numeric|exists:properties,id",

            // "tenant_id" => "required_without:landlord_id|numeric|exists:tenants,id",
            // "landlord_id" => "required_without:tenant_id|numeric|exists:landlords,id",
            "tenant_id" => [
                "nullable",
                "numeric",
                "exists:tenants,id",
                function ($attribute, $value, $fail) {
                    $landlordId = request()->input('landlord_id');
                    if (empty($value) && empty($landlordId)) {
                        $fail('Either tenant_id or landlord_id is required.');
                    }
                    if (!empty($value) && !empty($landlordId)) {
                        $fail('Only one of tenant_id or landlord_id should have a value.');
                    }
                },
            ],
            "landlord_id" => [
                "nullable",
                "numeric",
                "exists:landlords,id",
                function ($attribute, $value, $fail) {
                    $tenantId = request()->input('tenant_id');
                    if (empty($value) && empty($tenantId)) {
                        $fail('Either tenant_id or landlord_id is required.');
                    }
                    if (!empty($value) && !empty($tenantId)) {
                        $fail('Only one of tenant_id or landlord_id should have a value.');
                    }
                },
            ],


            "invoice_items" => "nullable|array",
            "invoice_items.*.name" => "required|string",
            "invoice_items.*.description" => "nullable|string",
            "invoice_items.*.quantity" => "required|numeric",
            "invoice_items.*.price" => "required|numeric",
            "invoice_items.*.tax" => "required|numeric",
            "invoice_items.*.amount" => "required|numeric",



            "total_amount"=>"required|numeric",

            "invoice_payments"=>"nullable|array",
            "invoice_payments.*.amount"=>"required|numeric",
            "invoice_payments.*.payment_method"=>"required|string",
            "invoice_payments.*.payment_date"=>"required|date",


        ];
    }
}
