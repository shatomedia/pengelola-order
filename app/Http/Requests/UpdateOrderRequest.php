<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required'],
            'payment_status' => ['required', 'in:Belum Bayar,DP,Lunas'],
            'jumlah_dibayar' => ['required', 'integer', 'min:0'],
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
