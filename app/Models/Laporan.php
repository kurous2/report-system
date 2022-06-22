<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;

class Laporan extends Model
{
    protected $appends = ['is_voted', 'is_up_vote'];
    
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
        return $this->belongsToMany('App\Models\User', 'laporan_user')->withPivot('is_up_vote');
    }

    public function getIsVotedAttribute(){
        if($this->voters()->where('laporan_user.user_id', Auth::id())->exists()){
            return true;
        }else{
            return false;
        }
    }

    public function getIsUpVoteAttribute(){
        if($this->getIsVotedAttribute()){
            $is_up_vote = $this->voters()->where('laporan_user.user_id', Auth::id())->latest()->first()->pivot->is_up_vote;
            if($is_up_vote == 1){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}
