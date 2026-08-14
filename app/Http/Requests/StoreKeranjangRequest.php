<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKeranjangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'produk' => ['required', 'exists:produk,id_produk'],
            'demo0' => ['required', 'integer', 'min:1', 'max:200']
        ];
    }
}
