<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDetailTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi dilakukan di controller.
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_transaksi' => 'required|exists:tb_transaksi,id',
            'id_paket' => 'required|exists:tb_paket,id',
            'qty' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string',
        ];
    }
}
