<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\MotelRoom\MotelRoomRepository;

class MotelRoomController extends Controller
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

    public function getMotelBySlug($slug){
        $room = $this->motel_room_repository->findBySlug($slug);
        //update view + 1
        $this->motel_room_repository->updateView($slug);
        $categories = $this->category_repository->getAllCategory();
        return view('home.detail',['motelroom'=>$room, 'categories'=>$categories]);
    }
}
