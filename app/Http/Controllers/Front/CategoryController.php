<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\MotelRoom\MotelRoomRepository;

class CategoryController extends Controller
{
    protected $category_repository;
    protected $motel_room_repository;

    public function __construct(
        CategoryRepository $category_repository,
        MotelRoomRepository $motel_room_repository
    ) {
        $this->category_repository = $category_repository;
        $this->motel_room_repository = $motel_room_repository;
    }
    public function getMotelByCategoryId($id){
        $list_motel = $this->motel_room_repository->getRoomByCategoryId($id);
        $categories = $this->category_repository->getAllCategory();
        return view('home.category',['listmotel'=>$list_motel,'categories'=>$categories]);
    }
}
