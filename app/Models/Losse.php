<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Losse extends Model
{
    use HasFactory;
    protected $fillable = [
                            'date',
                            'status',
                            'control_quality_id',
                            'branch_id',
                            'user_id'
                        ];
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }
    public function losse_detail()
    {
        return $this->hasMany('App\Models\LosseDetail');
    }
    public function Branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function quality_control()
    {
        return $this->belongsTo('App\Models\ProductionQualityControl', 'control_quality_id');
    }

}
