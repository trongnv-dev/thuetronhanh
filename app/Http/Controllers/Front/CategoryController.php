<?php

namespace App\Http\Controllers\Front;

use App\Categories;
use App\Motelroom;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function getMotelByCategoryId($id){
        $getmotel = Motelroom::where([['category_id',$id],['approve',1]])->paginate(3);
        $Categories = Categories::all();
        return view('home.category',['listmotel'=>$getmotel,'categories'=>$Categories]);
    }
}
