<?php

namespace App\Http\Controllers\Front;

use App\Categories;
use App\District;
use App\Motelroom;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $district = District::all();
        $categories = Categories::all();
        $hot_motelroom = Motelroom::where('approve', 1)->limit(6)->orderBy('count_view', 'desc')->get();
        $map_motelroom = Motelroom::where('approve', 1)->get();
        $listmotelroom = Motelroom::where('approve', 1)->paginate(4);
        return view('home.index', [
            'district'      => $district,
            'categories'    => $categories,
            'hot_motelroom' => $hot_motelroom,
            'map_motelroom' => $map_motelroom,
            'listmotelroom' => $listmotelroom
        ]);
    }
}
