<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOutletRequest extends FormRequest
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
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'tlp' => 'required|string|max:15',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama outlet wajib diisi.',
            'nama.string' => 'Nama outlet harus berupa teks.',
            'nama.max' => 'Nama outlet maksimal 100 karakter.',
            'alamat.required' => 'Alamat outlet wajib diisi.',
            'alamat.string' => 'Alamat outlet harus berupa teks.',
            'alamat.max' => 'Alamat outlet maksimal 255 karakter.',
            'tlp.required' => 'Nomor telepon outlet wajib diisi.',
            'tlp.string' => 'Nomor telepon outlet harus berupa teks.',
            'tlp.max' => 'Nomor telepon outlet maksimal 15 karakter.',
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
