<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionCost extends Model
{
    use HasFactory;
    protected $fillable = [
                            'date',
                            'status',
                            'branch_id',
                            'user_id',
                            'order_production_id'
                        ];
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function production_cost_detail()
    {
        return $this->hasMany('App\Models\ProductionCostDetail');
    }
    public function Branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function order_production()
    {
        return $this->belongsTo('App\Models\ProductionOrder', 'order_production_id');
    }

}
