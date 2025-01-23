<?php

namespace App\Repositories;

use App\Models\Paket;
use App\Interfaces\PaketRepositoryInterface;
class PaketRepository implements PaketRepositoryInterface
{
    public function index(){
        return Paket::all();
    }

    public function getById($id){
       return Paket::findOrFail($id);
    }

    public function store(array $data){
       return Paket::create($data);
    }

    public function update(array $data, $id)
{
    $paket = Paket::findOrFail($id);
    $paket->update($data);
    return $paket;
}

    public function delete($id){
       Paket::destroy($id);
    }
}
