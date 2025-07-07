<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'date',
        'client_id',
        'voucher_id',
        'amount',
        'observation',
        'type',
        'status',
        'user_id',
        'reason_deleted',
        'user_deleted',
    ];

    protected $casts = [
        'date' => 'date',
        'type' => 'boolean',
        'status' => 'boolean',
        'amount' => 'decimal:2',
    ];

    // Relaciones

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userDeleted()
    {
        return $this->belongsTo(User::class, 'user_deleted');
    }
}
