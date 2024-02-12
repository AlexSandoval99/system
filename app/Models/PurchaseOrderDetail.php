<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PurchasesDetail;

class PurchaseOrderDetail extends Model
{
    protected $fillable = ['purchase_order_id',
                           'material_id',
                           'description',
                           'quantity',
                           'amount',
                           'residue'];

    public function purchases_order()
    {
        return $this->belongsTo('App\Models\PurchasesOrder');
    }

    public function purchases_product()
    {
        return $this->belongsTo('App\Models\PurchasesProduct');
    }

    public function purchases_product_presentation()
    {
        return $this->belongsTo('App\Models\PurchasesProductPresentation', 'product_presentations_id');
    }

    public function purchases_details()
    {
        return $this->hasMany('App\Models\PurchasesDetail');
    }

    public function purchases_movements_detail()
    {
        return $this->hasMany('App\Models\PurchasesMovementDetail', 'purchases_order_detail_id');
    }
}
