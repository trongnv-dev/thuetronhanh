<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;

class MotelRoom extends Model
{
    use Sluggable;
    use SluggableScopeHelpers;
    protected $table = "motelrooms";
    public $timestamps = true;

    protected $fillable = [
        'id',
        'title',
        'description',
        'price',
        'area',
        'count_view',
        'address',
        'latlng',
        'images',
        'user_id',
        'category_id',
        'district_id',
        'utilities',
        'phone',
        'slug'
    ];

    public function category(){
        return $this->belongsTo('App\Models\Category','category_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function district(){
        return $this->belongsTo('App\Models\District','district_id','id');
    }
    public function reports(){
        return $this->hasMany('App\Models\Reports','id_motelroom','id');
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
