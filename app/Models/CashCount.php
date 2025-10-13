<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashCount extends Model
{
    use HasFactory;

    protected $table = 'cash_count';

    protected $fillable = [
        'cash_box_detail_id',
        'denominacion',
        'quantity',
    ];

    public function cash_box_detail()
    {
        return $this->belongsTo(CashBoxDetail::class, 'cash_box_detail_id');
    }
}
