<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'articulo_id','status','presentation_id','type_iva','average_cost'];
    
    public function articulo()
    {
        return $this->belongsTo('App\Models\Articulo');
    }
    public function scopeFilter($query)
    {
        return $query->where('status', true)->orderBy('description')->pluck('description', 'id');//ya hay tabla rae jajaj
    }
    public function presentation()
    {
        return $this->belongsTo('App\Models\Presentation');
    }
}
