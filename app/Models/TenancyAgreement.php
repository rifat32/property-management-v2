<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenancyAgreement extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'agreed_rent',
        'security_deposit_hold',
        'rent_payment_option',
        'tenant_contact_duration',
        'date_of_moving',
        'let_only_agreement_expired_date',
        'tenant_contact_expired_date',
        'rent_due_date',
        'no_of_occupants',
        'renewal_fee',
        'housing_act',
        'let_type',
        'terms_and_conditions',
        'agency_name',
        'landlord_name',
        'agency_witness_name',
        'tenant_witness_name',
        'agency_witness_address',
        'tenant_witness_address',
        'guarantor_name',
        'guarantor_address',
    ];
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'agreement_tenants', 'tenancy_agreement_id', 'tenant_id');
    }
}