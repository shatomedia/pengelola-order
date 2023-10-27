<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'no_urut' => ['required', 'integer'],
            'no_faktur' => ['required'],
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
            'total_qty' => ['nullable', 'integer'],
            'total_harga_jual' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
