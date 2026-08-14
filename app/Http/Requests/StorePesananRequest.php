<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alamat_kirim' => ['required', 'string'],
            'id_keranjang' => ['required', 'exists:keranjang,id_keranjang'],
            'variasi_harga' => ['nullable', 'string'],
            'sablon_harga' => ['nullable', 'string'],
            'variasi' => ['nullable', 'string'],
            'sablon' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
        ];
    }
}
