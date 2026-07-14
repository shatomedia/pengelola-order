<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePengeluaranRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'integer', 'exists:transaction_categories,id'],
            'jumlah' => ['required', 'integer', 'min:0'],
            'tanggal' => ['required', 'date'],
            'keterangan' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
