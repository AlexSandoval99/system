<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashBoxUser extends Model
{
    use HasFactory;

    protected $table = 'cash_box_users';

    protected $fillable = [
        'user_id',
        'cash_box_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relaciones

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashBox()
    {
        return $this->belongsTo(CashBox::class);
    }

    public function scopeFilter($query)
    {
        return $query->where('cash_box_users.status', true)
                     ->where('cash_box_users.user_id', auth()->user()->id)
                     ->join('cash_boxes', 'cash_box_users.cash_box_id', '=', 'cash_boxes.id')
                     ->where('cash_boxes.status', true)
                     ->orderBy('cash_boxes.name')
                     ->groupBy('cash_box_users.cash_box_id');
    }
}
