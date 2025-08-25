<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishProduction extends Model
{
    protected $table = "wish_sales";
    use HasFactory;
    protected $fillable = [
                            'date',
                            'branch_id',
                            'number',
                            'client_id',
                            'status',
                            'user_id'
                        ];
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function wish_production_details()
    {
        return $this->hasMany('App\Models\WishProductionDetail');
    }
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
