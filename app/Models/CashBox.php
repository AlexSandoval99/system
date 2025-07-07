<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashBox extends Model
{
    use HasFactory;

    protected $table = 'cash_boxes';

    protected $fillable = [
        'name',
        'observation',
        'status',
        'user_id',
        'voucher_box_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher_box()
    {
        return $this->belongsTo(VoucherBox::class);
    }
}
