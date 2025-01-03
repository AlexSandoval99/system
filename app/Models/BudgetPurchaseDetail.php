<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPurchaseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
                            'quantity',
                            'material_id',
                            'budget_id',
                            'price',
                            'total_price'
                        ];
    public function material()
    {
        return $this->belongsTo('App\Models\RawMaterial');
    }
    public function budget()
    {
        return $this->belongsTo('App\Models\BudgetPurchase', 'budget_id');
    }
}
