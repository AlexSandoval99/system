<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherBox extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'voucher_boxes';

    protected $fillable = [
        'name',
        'voucher_number',
        'branch_id',
        'from_invoice_number',
        'until_invoice_number',
        'user_id',
        'stamped_id',
    ];

    protected $casts = [
        'voucher_number' => 'integer',
        'from_invoice_number' => 'integer',
        'until_invoice_number' => 'integer',
    ];

    // Relaciones
    public function getVoucherFullnumberAttribute()
    {
        return str_pad($this->attributes['voucher_number'], 3, '0' ,STR_PAD_LEFT);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stamped()
    {
        return $this->belongsTo(Stamped::class);
    }

    public function cashBoxes()
    {
        return $this->hasMany(CashBox::class);
    }
}
