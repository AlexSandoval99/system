<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashBoxDetail extends Model
{
    use HasFactory;

    protected $table = 'cash_box_details';

    protected $fillable = [
        'cash_box_id',
        'cash_box_concept_id',
        'voucher_id',
        'payment_id',
        'type',
        'amount',
        'observation',
        'status',
        'user_id',
    ];

    protected $casts = [
        'type' => 'boolean',
        'status' => 'boolean',
        'amount' => 'decimal:2',
    ];

    // Relaciones

    public function cash_box()
    {
        return $this->belongsTo(CashBox::class);
    }

    public function concept()
    {
        return $this->belongsTo(CashBoxConcept::class, 'cash_box_concept_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
