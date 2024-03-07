<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionControlQualityDetail extends Model
{
    use HasFactory;
    protected $fillable = [
                            'observation',
                            'quantity',
                            'residue',
                            'articulo_id',
                            'production_quality_id',
                            'quality_id',
                        ];
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function production_control_quality()
    {
        return $this->belongsTo('App\Models\ProductionControlQuality','production_quality_id');
    }
    public function articulo()
    {   
        return $this->belongsTo('App\Models\Articulo','articulo_id');
    }
    public function production_quality()
    {
        return $this->belongsTo('App\Models\ProductionQuality','quality_id');
    }
    

}
