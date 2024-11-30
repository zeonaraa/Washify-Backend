<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOutletRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string',
            'tlp' => 'required|string|max:15',
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama outlet harus diisi.',
            'alamat.required' => 'Alamat outlet harus diisi.',
            'tlp.required' => 'Nomor telepon outlet harus diisi.',
        ];
    }
}
