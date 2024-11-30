<?php

namespace App\Repositories;

use App\Models\Transaksi;
use App\Interfaces\TransaksiRepositoryInterface;
class TransaksiRepository implements TransaksiRepositoryInterface
{
    public function index(){
        return Transaksi::all();
    }

    public function getById($id){
       return Transaksi::findOrFail($id);
    }

    public function store(array $data){
       return Transaksi::create($data);
    }

    public function update(array $data, $id)
{
    $transaksi = Transaksi::findOrFail($id);
    $transaksi->update($data);
    return $transaksi;
}

    public function delete($id){
       Transaksi::destroy($id);
    }
}
