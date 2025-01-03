<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetPurchase extends Model
{
    use HasFactory;
    protected $fillable = [
                            'date',
                            'status',
                            'provider_id',
                            'wish_id',
                            'name',
                            'ruc',
                            'confirmation_user_id',
                            'confirmation_date'
                        ];
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function budget_purchase_details()
    {
        return $this->hasMany('App\Models\BudgetPurchaseDetail', 'budget_id');
    }
    public function provider()
    {
        return $this->belongsTo('App\Models\Provider');
    }
    public function wish_purchase()
    {
        return $this->belongsTo('App\Models\WishPurchase','wish_id');
    }
}
