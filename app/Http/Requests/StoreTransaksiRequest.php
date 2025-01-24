<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTransaksiRequest extends FormRequest
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
            'id_member' => 'required|exists:tb_member,id',
            'tgl' => 'required|date',
            'tgl_bayar' => 'required|date',
            'batas_waktu' => 'required|date',
            'biaya_tambahan' => 'nullable|integer|min:0',
            'diskon' => 'nullable|numeric|min:0|max:5000',
            'pajak' => 'nullable|integer|min:0',
            'status' => 'required|in:baru,proses,selesai,diambil',
            'dibayar' => 'required|in:dibayar,belum_dibayar',
            'details' => 'required|array|min:1',
            'details.*.id_paket' => 'required|exists:tb_paket,id',
            'details.*.qty' => 'required|numeric|min:1|max:5',
            'details.*.keterangan' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'id_member.required' => 'ID Member wajib diisi.',
            'id_member.exists' => 'Member yang dipilih tidak valid.',
            'tgl.required' => 'Tanggal transaksi wajib diisi.',
            'tgl.date' => 'Format tanggal transaksi tidak valid.',
            'tgl_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'tgl_bayar.date' => 'Format tanggal pembayaran tidak valid.',
            'batas_waktu.required' => 'Batas waktu wajib diisi.',
            'batas_waktu.date' => 'Format batas waktu tidak valid.',
            'biaya_tambahan.integer' => 'Biaya tambahan harus berupa angka.',
            'biaya_tambahan.min' => 'Biaya tambahan minimal 0.',
            'diskon.numeric' => 'Diskon harus berupa angka.',
            'diskon.min' => 'Diskon minimal 0.',
            'diskon.max' => 'Diskon maksimal 5000.',
            'pajak.integer' => 'Pajak harus berupa angka.',
            'pajak.min' => 'Pajak minimal 0.',
            'status.required' => 'Status transaksi wajib diisi.',
            'status.in' => 'Status transaksi harus salah satu dari: baru, proses, selesai, diambil.',
            'dibayar.required' => 'Status pembayaran wajib diisi.',
            'dibayar.in' => 'Status pembayaran harus salah satu dari: dibayar, belum_dibayar.',
            'details.required' => 'Detail transaksi wajib diisi.',
            'details.array' => 'Detail transaksi harus berupa array.',
            'details.min' => 'Detail transaksi minimal 1 item.',
            'details.*.id_paket.required' => 'ID Paket wajib diisi.',
            'details.*.id_paket.exists' => 'Paket yang dipilih tidak valid.',
            'details.*.qty.required' => 'Quantity wajib diisi.',
            'details.*.qty.numeric' => 'Quantity harus berupa angka.',
            'details.*.qty.min' => 'Quantity minimal 1.',
            'details.*.qty.max' => 'Quantity maksimal 5.',
            'details.*.keterangan.string' => 'Keterangan harus berupa teks.',
            'details.*.keterangan.max' => 'Keterangan maksimal 255 karakter.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan validasi.',
            'errors' => $validator->errors()
        ], 422));
    }
}
