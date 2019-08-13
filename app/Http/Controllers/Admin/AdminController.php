<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\MotelRoom\MotelRoomRepository;
use App\Repositories\Report\ReportRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    protected $motel_room_repository;
    protected $category_repository;
    protected $report_repository;

    public function __construct(
        CategoryRepository $category_repository,
        MotelRoomRepository $motel_room_repository,
        ReportRepository $report_repository
    )
    {
        $this->category_repository = $category_repository;
        $this->motel_room_repository = $motel_room_repository;
        $this->report_repository = $report_repository;
    }

    public function getIndex()
    {
        $total_users_active = User::where('tinhtrang', 1)->get()->count();
        $total_users_deactive = User::where('tinhtrang', 0)->get()->count();
        $total_rooms_approved = $this->motel_room_repository->countRoomApproved(1);
        $total_rooms_unapproved = $this->motel_room_repository->countRoomApproved(0);
        $reports = $this->report_repository->getAllReport();
        return view('admin.index', [
            'total_users_active' => $total_users_active,
            'total_users_deactive' => $total_users_deactive,
            'total_rooms_approve' => $total_rooms_approved,
            'total_rooms_unapprove' => $total_rooms_unapproved,
            'total_report' => $reports->count(),
        ]);
    }

    public function getThongke()
    {
        $total_users_active = User::where('tinhtrang', 1)->get()->count();
        $total_users_deactive = User::where('tinhtrang', 0)->get()->count();
        $total_rooms_approved = $this->motel_room_repository->countRoomApproved(1);
        $total_rooms_unapproved = $this->motel_room_repository->countRoomApproved(0);
        $reports = $this->report_repository->getAllReport();
        return view('admin.thongke', [
            'total_users_active' => $total_users_active,
            'total_users_deactive' => $total_users_deactive,
            'total_rooms_approve' => $total_rooms_approved,
            'total_rooms_unapprove' => $total_rooms_unapproved,
            'total_report' => $reports->count(),
        ]);
    }

    public function getReport()
    {
        $reports = $this->report_repository->countReport();
        $motels = $this->motel_room_repository->getAllRoom();
        return view('admin.report', [
            'motels' => $motels,
            'reports' => $reports
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('admin');
    }

    public function getLogin()
    {
        return view('admin.login');
    }

    public function postLogin(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required',

        ], [
            'username.required' => 'Vui lòng nhập tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu'

        ]);
        if (Auth::attempt(['username' => $req->username, 'password' => $req->password])) {
            return redirect('admin');

        } else
            return redirect('admin/login')->with('thongbao', 'Đăng nhập không thành công');
    }

    public function getListUser()
    {
        $users = User::all();
        return view('admin.users.list', ['users' => $users]);
    }

    /* Motel room */
    public function getListMotel()
    {
        $motelrooms = $this->motel_room_repository->getAllRoom();
        return view('admin.motelroom.list', ['motelrooms' => $motelrooms]);
    }

    public function ApproveMotelroom($id)
    {
        $room = $this->motel_room_repository->updateApprove($id, 1);
        return redirect('admin/motelrooms/list')->with('thongbao', 'Đã kiểm duyệt bài đăng: ' . $room->title);
    }

    public function UnApproveMotelroom($id)
    {
        $room = $this->motel_room_repository->updateApprove($id, 0);
        return redirect('admin/motelrooms/list')->with('thongbao', 'Đã bỏ kiểm duyệt bài đăng: ' . $room->title);
    }

    public function DelMotelroom($id)
    {
        $this->motel_room_repository->deleteRoom($id);
        return redirect('admin/motelrooms/list')->with('thongbao', 'Đã xóa bài đăng');
    }

    /* user */
    public function getUpdateUser($id)
    {
        $user = User::find($id);
        return view('admin.users.edit', ['user' => $user]);
    }

    public function postUpdateUser(Request $request, $id)
    {
        $this->validate($request, [
            'HoTen' => 'required'
        ], [
            'HoTen.required' => 'Vui lòng nhập đầy đủ Họ Tên'
        ]);
        $user = User::find($id);
        $user->name = $request->HoTen;
        $user->right = $request->Quyen;
        $user->tinhtrang = $request->TinhTrang;

        if ($request->password != '') {
            $this->validate($request, [
                'password' => 'min:3|max:32',
                'repassword' => 'same:password',
            ], [
                'password.min' => 'password phải lớn hơn 3 và nhỏ hơn 32 kí tự',
                'password.max' => 'password phải lớn hơn 3 và nhỏ hơn 32 kí tự',
                'repassword.same' => 'Nhập lại mật khẩu không đúng',
                'repassword.required' => 'Vui lòng nhập lại mật khẩu',
            ]);
            $user->password = bcrypt($request->password);
        }


        $user->save();
        return redirect('admin/users/edit/' . $id)->with('thongbao', 'Chỉnh sửa thành công tài khoản ' . $request->username . ' .');
    }

    public function DeleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('admin/users/list')->with('thongbao', 'Đã xóa người dùng khỏi danh sách. Những bài đăng của người dùng này cũng bị xóa');
    }
}
