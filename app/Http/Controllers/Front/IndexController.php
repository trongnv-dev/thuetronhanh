<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\MotelRoom\MotelRoomRepository;
use App\Repositories\District\DistrictRepository;
use App\Repositories\User\UserRepository;

class IndexController extends Controller
{
    protected $category_repository;
    protected $motel_room_repository;
    protected $district_repository;
    protected $user_repository;

    public function __construct(
        CategoryRepository $category_repository,
        MotelRoomRepository $motel_room_repository,
        DistrictRepository $district_repository,
        UserRepository $user_repository
    ){
        $this->category_repository = $category_repository;
        $this->motel_room_repository = $motel_room_repository;
        $this->district_repository = $district_repository;
        $this->user_repository = $user_repository;
    }

    public function index()
    {
        $district = $this->district_repository->getAllDistrict();
        $categories = $this->category_repository->getAllCategory();
        $hot_motel_room = $this->motel_room_repository->getHotRoom();
        $map_motel_room = $this->motel_room_repository->getAllRoomApproved();
        $list_motel_room = $this->motel_room_repository->getRoomPaginate();
        return view('home.index', [
            'district'      => $district,
            'categories'    => $categories,
            'hot_motelroom' => $hot_motel_room,
            'map_motelroom' => $map_motel_room,
            'listmotelroom' => $list_motel_room
        ]);
    }
}
