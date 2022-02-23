<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasmany('App/Models/Post');
    }

    public function likes()
    {
        return $this->hasmany('App/Models/Like');
    }

    public function likePostGet()
    {
        return $this->hasManyThrough(
            'App\Models\Post', //取得したいテーブル
            'App\Models\Like', //中間テーブル
            'user_id', //中間テーブルの外部キー
            'id', //取得したいテーブルの外部キー
            null, //呼び出し元テーブルのローカルキー
            'post_id' //中間テーブルのローカルキー
        );
    }
}
