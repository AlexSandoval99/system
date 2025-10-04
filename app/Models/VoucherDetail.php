<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{
    use HasFactory;

    protected $table = 'voucher_details';

    protected $fillable = [
        'voucher_id',
        'articulo_id',
        'description',
        'quantity',
        'amount',
        'excenta',
        'iva5',
        'iva10',
    ];

    // Relaciones
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class);
    }
}
