<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = "users";
    public $timestamps = true;
    protected $fillable = [
        'id',
        'name',
        'username',
        'email',
        'password',
        'right',
        'phone',
        'avatar',
        'tinhtrang',
        'remember_token',
    ];

    public function motelroom(){
        return $this->hasMany('App\Models\MotelRoom','user_id','id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
