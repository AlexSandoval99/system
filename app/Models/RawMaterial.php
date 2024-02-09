<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['description', 'articulo_id','status'];
    
    public function articulo()
    {
        return $this->belongsTo('App\Models\Articulo');
    }
    public function scopeFilter($query)
    {
        return $query->where('status', true)->orderBy('description')->pluck('description', 'id');
    }
}
