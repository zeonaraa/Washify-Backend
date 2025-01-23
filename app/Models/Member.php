<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $table = 'tb_member';
    protected $fillable = ['nama', 'alamat', 'jenis_kelamin', 'tlp', 'id_outlet'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'id_member');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }
}
