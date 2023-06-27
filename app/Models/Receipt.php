<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'property_address',
        'amount',
        'receipt_by',
        'receipt_date',
        "created_by"
    ];

    public function tenant() {
        return $this->belongsTo(Tenant::class,'tenant_id','id');
    }

    public function property_address() {
        return $this->belongsTo(Property::class,'property_address','address');
    }

}
