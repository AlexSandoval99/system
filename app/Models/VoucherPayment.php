<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'voucher_id',
        'payment_method_id',
        'amount',
        'check_number',
        'check_expiration',
        'status'
    ];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }

    public function voucherCollect()
    {
        return $this->belongsTo('App\Models\VoucherCollect');
    }
}
