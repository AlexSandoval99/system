<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashBoxConcept extends Model
{
    use HasFactory;

    protected $table = 'cash_box_concepts';

    protected $fillable = [
        'name',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relación con el usuario que creó o administra el concepto
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
