<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    public $timestamps = true;

    public function motelroom(){
        return $this->hasMany('App\Models\MotelRoom','district_id','id');
    }
}
