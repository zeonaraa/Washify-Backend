<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTransaksiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'id_transaksi' => $this->id_transaksi,
            'id_paket' => $this->id_paket,
            'qty' => $this->qty,
            'keterangan' => $this->keterangan
        ];
    }
}
