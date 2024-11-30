<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDetailTransaksiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_paket' => 'nullable|exists:tb_paket,id',
            'qty' => 'nullable|numeric|min:1',
            'keterangan' => 'nullable|string',
        ];
    }
}
