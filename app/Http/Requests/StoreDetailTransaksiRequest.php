<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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
            'id_paket' => 'required|integer|exists:pakets,id',
            'qty' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'id_paket.required' => 'ID Paket wajib diisi.',
            'id_paket.integer' => 'ID Paket harus berupa angka.',
            'id_paket.exists' => 'Paket yang dipilih tidak valid.',
            'qty.required' => 'Quantity wajib diisi.',
            'qty.integer' => 'Quantity harus berupa angka.',
            'qty.min' => 'Quantity minimal 1.',
            'keterangan.string' => 'Keterangan harus berupa teks.',
            'keterangan.max' => 'Keterangan maksimal 255 karakter.',
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
