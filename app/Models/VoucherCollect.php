<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherCollect extends Model
{
    use HasFactory;
    protected $fillable = [
        'voucher_id',
        'number',
        'expiration',
        'amount',
        'residue'
    ];

    public function voucher()
    {
        return $this->belongsTo('App\Models\Voucher');
    }
}
