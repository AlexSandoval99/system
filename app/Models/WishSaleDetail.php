<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishSaleDetail extends Model
{
    protected $table = "wish_sale_details";
    use HasFactory;
    protected $fillable = [
                            'wish_sale_id',
                            'articulo_id',
                            'quantity'
                        ];
    public function articulo()
    {
        return $this->belongsTo('App\Models\Articulo');
    }
    public function wish_sales()
    {
        return $this->belongsTo('App\Models\WishSale', 'wish_sale_id');
    }
}
