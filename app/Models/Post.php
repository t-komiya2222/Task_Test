<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user(){
        return $this->belongsto('App\Models\User');
    }

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'description'
    ];
}
