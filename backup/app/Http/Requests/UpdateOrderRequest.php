<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required'],
            'nama_pembeli' => ['required'],
            'alamat' => ['required'],
            'no_hp' => ['required'],
            'order_via' => ['required'],
            'tgl_order' => ['required', 'date', 'before_or_equal:tgl_kirim'],
            'tgl_kirim' => ['required', 'date', 'after_or_equal:tgl_order'],
            'title' => ['required'],
            'background' => ['required'],
            'request' => ['required'],
            'keterangan' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
