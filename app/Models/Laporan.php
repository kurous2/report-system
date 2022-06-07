<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class Laporan extends Model
{
    protected $fillable = [
        'unit',
        'uraian',
        'solusi',
        'gambar',
        'categories_id',
        'users_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }
}
