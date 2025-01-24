<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePaketRequest extends FormRequest
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
            'id_outlet' => 'nullable|exists:tb_outlet,id',
            'jenis' => 'nullable|in:kiloan,selimut,bed_cover,kaos,lain',
            'nama_paket' => 'nullable|string|max:100',
            'harga' => 'nullable|integer|min:1000',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'id_outlet.exists' => 'Outlet yang dipilih tidak valid.',
            'jenis.in' => 'Jenis paket harus salah satu dari: kiloan, selimut, bed_cover, kaos, lain.',
            'nama_paket.string' => 'Nama paket harus berupa teks.',
            'nama_paket.max' => 'Nama paket maksimal 100 karakter.',
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
