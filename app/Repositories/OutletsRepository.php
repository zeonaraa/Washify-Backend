<?php

namespace App\Repositories;
use App\Models\Outlet;
use App\Interfaces\OutletsRepositoryInterface;
class OutletsRepository implements OutletsRepositoryInterface
{
    public function index(){
        return Outlet::all();
    }

    public function getById($id){
       return Outlet::findOrFail($id);
    }

    public function store(array $data){
       return Outlet::create($data);
    }

    public function update(array $data,$id){
       return Outlet::whereId($id)->update($data);
    }

    public function delete($id){
       Outlet::destroy($id);
    }
}
