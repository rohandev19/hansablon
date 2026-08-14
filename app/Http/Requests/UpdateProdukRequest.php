<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Assuming authorization is handled by middleware or policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_produk' => ['required', 'string'],
            'pilih_kategori' => ['required', 'exists:kategori,id_kategori'],
            'deskripsi_produk' => ['required', 'string'],
            'stok' => ['required', 'integer', 'min:0'],
            'harga_produk1' => ['nullable', 'string'],
            'harga_produk2' => ['nullable', 'string'],
            'harga_produk3' => ['nullable', 'string'],
            'harga_produk4' => ['nullable', 'string'],
            'harga_produk5' => ['nullable', 'string'],
            'img1' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'img2' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'img3' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'img4' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
