<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function person(){
        return $this->belongsto('App\Models\User');
    }

    /*
    public function getData()
    {
        return $this->id.'： ' .$this->user->name.' イメージ:'. $this->id;
    }
    */
}
