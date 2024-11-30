<?php

namespace App\Repositories;

use App\Models\DetailTransaksi;
use App\Interfaces\DetailTransaksiRepositoryInterface;
class DetailTransaksiRepository implements DetailTransaksiRepositoryInterface
{
    public function index(){
        return DetailTransaksi::all();
    }

    public function getById($id){
       return DetailTransaksi::findOrFail($id);
    }

    public function getByTransactionId($id_transaksi)
{
    return DetailTransaksi::where('id_transaksi', $id_transaksi)->with('paket')->get();
}


    public function store(array $data){
       return DetailTransaksi::create($data);
    }

    public function update(array $data, $id)
{
    $detailtransaksi = DetailTransaksi::findOrFail($id);
    $detailtransaksi->update($data);
    return $detailtransaksi;
}

    public function delete($id){
       DetailTransaksi::destroy($id);
    }
}
