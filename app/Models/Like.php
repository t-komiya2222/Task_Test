<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'post_id', 'user_id',
    ];

    public function posts()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }
}
