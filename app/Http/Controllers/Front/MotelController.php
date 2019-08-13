<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\MotelRoom\MotelRoomRepository;
use App\Repositories\Report\ReportRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class MotelController extends Controller
{
    protected $motel_room_repository;
    protected $report_repository;

    public function __construct(
        MotelRoomRepository $motel_room_repository,
        ReportRepository $report_repository
    ) {
        $this->motel_room_repository = $motel_room_repository;
        $this->report_repository = $report_repository;
    }
    public function searchMotelAjax(Request $request){
        $rooms = $this->motel_room_repository->getRoomSearchAjax($request->id_ditrict, $request->min_price, $request->max_price, $request->id_category);
		$arr_result_search = [];
		foreach ($rooms as $room) {
			$arrlatlng = json_decode($room->latlng,true);
			$arrImg = json_decode($room->images,true);
            $arr_result_search[] = [
                "id"      => $room->id,
                "lat"     => $arrlatlng[0],
                "lng"     => $arrlatlng[1],
                "title"   => $room->title,
                "slug"    => $room->slug,
                "address" => $room->address,
                "image"   => $arrImg[0],
                "phone"   => $room->phone
            ];
		}
		return json_encode($arr_result_search);
	}

	public function user_del_motel($id){
		if (!Auth::check()) {
			return redirect('user/login');
		} else {
			$room = $this->motel_room_repository->getRoomById($id);
			if(Auth::id() != $room->user_id ) {
                return redirect('user/profile')->with('thongbao', 'Bạn không có quyền xóa bài đăng không phải là của bạn!');
            }else {
				$room->delete();
				return redirect('user/profile')->with('thongbao','Đã xóa bài đăng của bạn');
			}
		}
	}

	public function userReport($id,Request $request){
		$ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    $data = [
            'ip_address'   => $ipaddress,
            'id_motelroom' => $id,
            'status'       => $request->baocao,
        ];
        $this->report_repository->addReport($data);
        $room = $this->motel_room_repository->getRoomById($id);
		return redirect('phongtro/'.$room->slug)->with('thongbao','Cảm ơn bạn đã báo cáo, đội ngũ chúng tôi sẽ xem xét');
	}
}
