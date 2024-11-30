<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
class UserRepository implements UserRepositoryInterface
{
    public function index(){
        return User::all();
    }

    public function getById($id){
       return User::findOrFail($id);
    }

    public function store(array $data){
       return User::create($data);
    }

    public function update(array $data, $id)
{
    $user = User::findOrFail($id);
    $user->update($data);
    return $user;
}

    public function delete($id){
       User::destroy($id);
    }
}
