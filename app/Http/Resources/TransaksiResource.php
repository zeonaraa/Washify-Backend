<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
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
            'id_outlet' => $this->id_outlet,
            'kode_invoice' => $this->kode_invoice,
            'id_member' => $this->id_member,
            'tgl' => $this->tgl,
            'batas_waktu' => $this->batas_waktu,
            'tgl_bayar' => $this->tgl_bayar,
            'biaya_tambahan' => $this->biaya_tambahan,
            'diskon' => $this->diskon,
            'pajak' => $this->pajak,
            'status' => $this->status,
            'dibayar' => $this->dibayar,
            'id_user' => $this->id_user,
            'details' => $this->detailTransaksi->map(function ($detail) {
                return [
                    'id_paket' => $detail->id_paket,
                    'qty' => $detail->qty,
                    'keterangan' => $detail->keterangan,
                ];
            }),
        ];
    }
}
