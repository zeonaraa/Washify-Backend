<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTransaksiRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id_member' => 'required|exists:tb_member,id',
            'tgl' => 'required|date',
            'tgl_bayar' => 'required|date',
            'batas_waktu' => 'required|date',
            'biaya_tambahan' => 'nullable|integer',
            'diskon' => 'nullable|numeric|min:0|max:5000',
            'pajak' => 'nullable|integer',
            'status' => 'required|in:baru,proses,selesai,diambil',
            'dibayar' => 'required|in:dibayar,belum_dibayar',
            'details' => 'required|array',
            'details.*.id_paket' => 'required|exists:tb_paket,id',
            'details.*.qty' => 'required|numeric|min:1|max:5',
            'details.*.keterangan' => 'nullable|string',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
