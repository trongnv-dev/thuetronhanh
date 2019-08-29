<?php

namespace App\Http\Controllers;

use App\Repositories\Category\CategoryRepository;
use App\Repositories\MotelRoom\MotelRoomRepository;
use App\Repositories\District\DistrictRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
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
    ) {
        $this->category_repository = $category_repository;
        $this->motel_room_repository = $motel_room_repository;
        $this->district_repository = $district_repository;
        $this->user_repository = $user_repository;
    }

	/* Register */
   	public function get_register(){
         $categories = $this->category_repository->getAllCategory();
   		return view('home.register',['categories'=>$categories]);
   	}

   	public function post_register(Request $request){
   		
   		$request->validate([
   			'txtuser' => 'required|unique:users,username',
   			'txtmail' => 'required|email|unique:users,email',
   			'txtpass' => 'required|min:6',
   			'txt-repass' => 'required|same:txtpass',
   			'txtname' => 'required'
   		],[
   			'txtuser.required' => 'Vui lòng nhập tài khoản',
   			'txtuser.unique' => 'Tài khoản đã tồn tại trên hệ thống',
   			'txtmail.unique' => 'Email đã tồn tại trên hệ thống',
   			'txtmail.required' => 'Vui lòng nhập Email',
   			'txtpass.required' => 'Vui lòng nhập mật khẩu',
   			'txtpass.min' => 'Mật khẩu phải lớn hơn 6 kí tự',
   			'txt-repass.required' => 'Vui lòng nhập lại mật khẩu',
   			'txt-repass.same' => 'Mật khẩu nhập lại không trùng khớp',
   			'txtname.required' => 'Nhập tên hiển thị'
   		]);
   		$data = [
            'username' => $request->txtuser,
            'name' => $request->txtname,
            'password' => bcrypt($request->txtpass),
            'email' => $request->txtmail,
        ];
        $this->user_repository->addUser($data);
   		return redirect('/user/register')->with('success','Đăng kí thành công');
   	}
   	/* Login */
   	public function get_login(){
         $categories = $this->category_repository->getAllCategory();
   		return view('home.login',['categories'=>$categories]);
   	}
   	public function post_login(Request $request){
   		$request->validate([
   			'txtuser' => 'required',
   			'txtpass' => 'required',
   			
   		],[
   			'txtuser.required' => 'Vui lòng nhập tài khoản',
   			'txtpass.required' => 'Vui lòng nhập mật khẩu'
   			
   		]);
   		if(Auth::attempt(['username'=>$request->txtuser,'password'=>$request->txtpass])){
    		return redirect('/');
    	}
    	else 
    		return redirect('user/login')->with('warn','Tài khoản hoặc mật khẩu không đúng');	
   	}
   	public function logout(){
   		Auth::logout();
		return redirect('user/login');
   	}

   	public function getprofile(){
   	     $mypost= $this->motel_room_repository->getRoomByUser(Auth::id());
         $categories = $this->category_repository->getAllCategory();
         return view('home.profile',[
            'categories'=>$categories,
            'mypost'=> $mypost
         ]);
      }

    public function getEditprofile(){
     $user = $this->user_repository->getUserById(Auth::id());
     $categories = $this->category_repository->getAllCategory();
     return view('home.edit-profile',[
        'categories'=>$categories,
        'user'=> $user
     ]);
  }
    public function postEditprofile(Request $request){
         $user = $this->user_repository->getUserById(Auth::id());
         $data = [];
         if ($request->hasFile('avtuser')){
            $file = $request->file('avtuser');
            $exten = $file->getClientOriginalExtension();
            if($exten != 'jpg' && $exten != 'png' && $exten !='jpeg' && $exten != 'JPG' && $exten != 'PNG' && $exten !='JPEG' ) {
                return redirect('user/profile/edit')->with('thongbao','Bạn chỉ được upload hình ảnh có định dạng JPG,JPEG hoặc PNG');
            }
            $avatar = 'avatar-'.$user->username . '-' . $user->id .'-' .time() . '.' .$exten;
//            while (file_exists('uploads/avatars/'.$avatar)) {
//                 $avatar = 'avatar-'.$user->username.'-'.time().'.'.$exten;
//            }
            if(file_exists('uploads/avatars/'.$user->avatar)){
                unlink('uploads/avatars/'.$user->avatar);
            }

            $file->move('uploads/avatars',$avatar);
            $data['avatar'] = $avatar;
         }
         $this->validate($request,[
               'txtname' => 'min:3|max:20'
            ],[
               'txtname.min' => 'Tên phải lớn hơn 3 và nhỏ hơn 20 kí tự',
               'txtname.max' => 'Tên phải lớn hơn 3 và nhỏ hơn 20 kí tự'
         ]);
         if(($request->txtpass != '' ) || ($request->retxtpass != '')){
            $this->validate($request,[
               'txtpass' => 'min:3|max:32',
               'retxtpass' => 'same:txtpass',
            ],[
               'txtpass.min' => 'password phải lớn hơn 3 và nhỏ hơn 32 kí tự',
               'txtpass.max' => 'password phải lớn hơn 3 và nhỏ hơn 32 kí tự',
               'retxtpass.same' => 'Nhập lại mật khẩu không đúng',
               'retxtpass.required' => 'Vui lòng nhập lại mật khẩu',
            ]);
             $data['password'] = $request->txtpass;
         }
         $data['name'] = $request->txtname;
         $this->user_repository->editUser($user->id, $data);
         return redirect('user/profile/edit')->with('thongbao','Cập nhật thông tin thành công');
      }

   	/* Đăng tin */
   	public function get_dangtin(){
         $district = $this->district_repository->getAllDistrict();
         $categories = $this->category_repository->getAllCategory();
   		return view('home.dangtin',[
            'district'=>$district,
            'categories'=>$categories
         ]);
   	}
   	public function post_dangtin(Request $request){

         $request->validate([
            'txttitle' => 'required',
            'txtaddress' => 'required',
            'txtprice' => 'required',
            'txtarea' => 'required',
            'txtphone' => 'required',
            'txtdescription' => 'required',
            'txtaddress' => 'required',
         ],
         [  
            'txttitle.required' => 'Nhập tiêu đề bài đăng',
            'txtaddress.required' => 'Nhập địa chỉ phòng trọ',
            'txtprice.required' => 'Nhập giá thuê phòng trọ',
            'txtarea.required' => 'Nhập diện tích phòng trọ',
            'txtphone.required' => 'Nhập SĐT chủ phòng trọ (cần liên hệ)',
            'txtdescription.required' => 'Nhập mô tả ngắn cho phòng trọ',
            'txtaddress.required' => 'Nhập hoặc chọn địa chỉ phòng trọ trên bản đồ'
         ]);
        
         /* Check file */ 
         $json_img = "";
         if ($request->hasFile('hinhanh')){
            $arr_images = array();
            $inputfile =  $request->file('hinhanh');
            foreach ($inputfile as $filehinh) {
                $img_name = Str::slug($filehinh->getClientOriginalName(), '-');
                $namefile = "phongtro-".str_random(5)."-".$img_name;
                while (file_exists('uploads/images'.$namefile)) {
                    $namefile = "phongtro-".str_random(5)."-".$img_name;
                }
              $arr_images[] = $namefile;
              $filehinh->move('uploads/images',$namefile);
            }
            $json_img =  json_encode($arr_images,JSON_FORCE_OBJECT);
         }
         else {
            $arr_images[] = "no_img_room.png";
            $json_img = json_encode($arr_images,JSON_FORCE_OBJECT);
         }
         /* tiện ích*/
         $json_tienich = json_encode($request->tienich,JSON_FORCE_OBJECT);
         /* ----*/ 
         /* get LatLng google map */ 
         $arrlatlng = array();
         $arrlatlng[] = $request->txtlat;
         $arrlatlng[] = $request->txtlng;
         $json_latlng = json_encode($arrlatlng,JSON_FORCE_OBJECT);

         /* --- */
         /* New Phòng trọ */
         $motel = [
             'title'       => $request->txttitle,
             'description' => $request->txtdescription,
             'price'       => $request->txtprice,
             'area'        => $request->txtarea,
             'count_view'  => 0,
             'address'     => $request->txtaddress,
             'latlng'      => $json_latlng,
             'utilities'   => $json_tienich,
             'images'      => $json_img,
             'user_id'     => Auth::user()->id,
             'category_id' => $request->idcategory,
             'district_id' => $request->iddistrict,
             'phone'       => $request->txtphone,
            ];
         $this->motel_room_repository->addNewRoom($motel);
         return redirect('/user/dangtin')->with('success','Đăng tin thành công. Vui lòng đợi Admin kiểm duyệt');
      }
}
