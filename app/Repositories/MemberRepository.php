<?php

namespace App\Repositories;

use App\Models\Member;
use App\Interfaces\MemberRepositoryInterface;
class MemberRepository implements MemberRepositoryInterface
{
    public function index(){
        return Member::all();
    }

    public function getById($id){
       return Member::findOrFail($id);
    }

    public function store(array $data){
       return Member::create($data);
    }

    public function update(array $data, $id)
{
    $member = Member::findOrFail($id);
    $member->update($data);
    return $member;
}

    public function delete($id){
       Member::destroy($id);
    }
}
