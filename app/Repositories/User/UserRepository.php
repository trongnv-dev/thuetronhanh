<?php
namespace App\Repositories\User;

use App\Models\User;

Class UserRepository {
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserById($id){
        $user = $this->user->find($id);
        return $user;
    }

    public function addUser($data){
        $insert_data = [
            'username' => $data['username'],
            'name' => $data['name'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
        ];
        $this->user->create($insert_data);
    }

    public function editUser($id, $data){
        $update_data = [
            'name' => $data['name'],
        ];
        if(isset($update_data['password'])){
            $update_data['password'] = bcrypt($data['password']);
        }
        if(isset($update_data['password'])){
            $update_data['avatar'] = $data['avatar'];
        }
        $this->user->where('id', $id)->update($update_data);
    }
}
