<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bukti_bayar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'desain' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf,cdr,psd,ai', 'max:10240'],
            'metode' => ['required', 'string'],
            'request_desain' => ['nullable', 'string']
        ];
    }
}
