<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePemasukanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'kategori_id' => ['required', 'integer', 'exists:transaction_categories,id'],
            'sumber' => ['required'],
            'order_id' => ['nullable', 'integer', 'exists:orders,id'],
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
