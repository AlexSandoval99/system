<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollectionDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_date',
        'amount_cash',
        'amount_check',
        'cash_box_detail_id',
        'bank_id',
        'account_number',
        'user_id',
        'cash_box_id',
        'observations',
        'status',
    ];

    public function cash_box_detail()
    {
        return $this->belongsTo(CashBoxDetail::class, 'cash_box_detail_id');
    }
}
