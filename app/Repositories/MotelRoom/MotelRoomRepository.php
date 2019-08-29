<?php
namespace App\Repositories\MotelRoom;

use App\Models\MotelRoom;
use Illuminate\Support\Str;
Class MotelRoomRepository {
    protected $motel_room;

    public function __construct(MotelRoom $motel_room)
    {
        $this->motel_room = $motel_room;
    }

    public function getAllRoom(){
        $rooms = $this->motel_room->all();
        return $rooms;
    }

    public function findBySlug($slug){
        $room = $this->motel_room->findBySlug($slug);
        return $room;
    }

    public function getRoomById($id){
        $room = $this->motel_room->find($id);
        return $room;
    }

    public function getAllRoomApproved(){
        $rooms = $this->motel_room->where('approve', 1)->get();
        return $rooms;
    }

    public function countRoomApproved($approve = 1){
        $rooms = $this->motel_room->where('approve', $approve)->get()->count();
        return $rooms;
    }

    public function getRoomPaginate($pagi = 4){
        $rooms = $this->motel_room->where('approve', 1)->paginate($pagi);
        return $rooms;
    }

    public function getHotRoom(){
        $hot_motel_room = $this->motel_room->where('approve', 1)
                                                    ->orderBy('count_view', 'desc')
                                                    ->limit(6)
                                                    ->get();
        return $hot_motel_room;
    }

    public function getRoomByCategoryId($id){
        $rooms = $this->motel_room->where([['category_id',$id],['approve',1]])->paginate(3);
        return $rooms;
    }

    public function getRoomByUser($user_id){
        $rooms = $this->motel_room->where('user_id', $user_id)->get();
        return $rooms;
    }

    public function addNewRoom($data){
        $insert_data = [
            'title'       => $data['title'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'area'        => $data['area'],
            'count_view'  => $data['count_view'],
            'address'     => $data['address'],
            'latlng'      => $data['latlng'],
            'utilities'   => $data['utilities'],
            'images'      => $data['images'],
            'user_id'     => $data['user_id'],
            'category_id' => $data['category_id'],
            'district_id' => $data['district_id'],
            'phone'       => $data['phone'],
        ];
        $data = $this->motel_room->create($insert_data);
        $room = $this->motel_room->find($data->id);
        $room->slug = Str::slug($room->title, '-') . '-' . $room->id;
        $room->save();
    }

    public function getRoomSearchAjax($district_id, $minPrice, $maxPrice, $category_id, $approve = 1){
        $ar_where = [
            ['district_id', $district_id],
            ['price','>=', $minPrice],
            ['price','<=', $maxPrice],
            ['category_id', $category_id],
            ['approve',$approve]
        ];
        $rooms = $this->motel_room->where($ar_where)->get();
        return $rooms;
    }

    public function updateView($slug){
        $room = $this->motel_room->findBySlug($slug);
        $room->count_view = $room->count_view +1;
        $room->save();
    }

    public function updateApprove($id, $approve){
        $room = $this->motel_room->find($id);
        $room->approve = $approve;
        $room->save();
        return $room;
    }

    public function deleteRoom($id){
        $room = $this->motel_room->find($id);
        $room->del_flg = 1;
        $room->save();
        return $room;
    }
}
