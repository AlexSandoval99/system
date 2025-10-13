<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCollectPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'voucher_id',
        'voucher_collect_id',
        'amount'
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
