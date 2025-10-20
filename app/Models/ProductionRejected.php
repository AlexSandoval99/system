<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionRejected extends Model
{
    use HasFactory;

    protected $table = 'production_rejected';
    protected $fillable = [
        'articulo_id',
        'owner_id',
        'owner_type',
        'quantity',
    ];

    /**
     * Relación con el artículo.
     * Un rechazo pertenece a un artículo.
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }

    public function owner()
    {
        return $this->morphTo();
    }
}
