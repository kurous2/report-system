<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;

class Laporan extends Model
{
    protected $fillable = [
        'subjek',
        'unit',
        'uraian',
        'solusi',
        'gambar',
        'status',
        'vote',
        'tanggapan',
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

    public function voters()
    {
        return $this->belongsToMany('App\Models\User', 'laporan_user');
    }
}
