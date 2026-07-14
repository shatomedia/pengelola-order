<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePengeluaranBerulangRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'integer', 'exists:transaction_categories,id'],
            'nama' => ['required'],
            'jumlah_estimasi' => ['required', 'integer', 'min:0'],
            'tanggal_jatuh_tempo' => ['required', 'integer', 'min:1', 'max:28'],
            'aktif' => ['nullable', 'boolean'],
            'keterangan' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
