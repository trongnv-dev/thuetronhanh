<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table ="categories";
    public $timestamps = true;

    public function motelroom(){
        return $this->hasMany('App\Models\MotelRoom','category_id','id');
    }
}
