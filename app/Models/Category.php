<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Laporan;

class Category extends Model
{
    protected $fillable = [
        'nama',
    ];
    
    public function laporans()
    {
        return $this->hasMany(Laporan::class, 'laporans_id', 'id');
    }
}
