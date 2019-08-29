<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    public $timestamps = true;

    protected $fillable = [
        'id',
        'ip_address',
        'id_motelroom',
        'status',
    ];
}
