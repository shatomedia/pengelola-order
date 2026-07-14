<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required'],
            'payment_status' => ['required', 'in:Belum Bayar,DP,Lunas'],
            'jumlah_dibayar' => ['nullable', 'integer', 'min:0'],
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
            'produk_id' => ['required', 'array', 'min:1'],
            'produk_id.*' => ['required', 'integer', 'exists:products,id', 'distinct'],
            'qty' => ['required', 'array', 'min:1'],
            'qty.*' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'produk_id.*.distinct' => 'Produk tidak boleh dipilih lebih dari sekali dalam satu pesanan.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
