<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddOrderProdukRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'order_id' => ['required', 'integer'],
            'produk_id' => ['required', 'integer'],
            'qty' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
