<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamWork extends Model
{
    use HasFactory;
    protected $fillable = [
                            'name',
                            'work',
                            'status',
                        ];

    public function scopeFilter($query)
    {
        return $query->where('status', true)->orderBy('name')->pluck('name', 'id');
    }
}
