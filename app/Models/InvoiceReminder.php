<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceReminder extends Model
{
    use HasFactory;
    protected $fillable = [
        "send_reminder",
        "reminder_date",
        "invoice_id",
    ];

    public function invoice(){
        return $this->belongsTo(Invoice::class,'invoice_id', 'id');
    }
}
