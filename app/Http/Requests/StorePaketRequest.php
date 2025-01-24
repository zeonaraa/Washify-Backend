<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePaketRequest extends FormRequest
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
            'id_outlet' => 'required|exists:tb_outlet,id',
            'jenis' => 'required|in:kiloan,selimut,bed_cover,kaos,lain',
            'nama_paket' => 'required|string|max:100',
            'harga' => 'required|integer|min:1000',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'id_outlet.required' => 'ID Outlet wajib diisi.',
            'id_outlet.exists' => 'Outlet yang dipilih tidak valid.',
            'jenis.required' => 'Jenis paket wajib diisi.',
            'jenis.in' => 'Jenis paket harus salah satu dari: kiloan, selimut, bed_cover, kaos, lain.',
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'nama_paket.string' => 'Nama paket harus berupa teks.',
            'nama_paket.max' => 'Nama paket maksimal 100 karakter.',
            'harga.required' => 'Harga paket wajib diisi.',
            'harga.integer' => 'Harga paket harus berupa angka.',
            'harga.min' => 'Harga paket minimal Rp 1.000.',
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
