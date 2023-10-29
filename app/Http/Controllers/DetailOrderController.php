<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddOrderProdukRequest;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DetailOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:order-edit', ['only' => ['store','delete']]);
    }

    public function store(AddOrderProdukRequest $request, $id)
    {
        try {
            $productId = $request->input('produk_id');
            $qty = $request->input('qty');
            $product = Product::findOrFail($productId);

            if ($qty > $product->stok){
                alert()->warning('Oops..', 'Stok tidak mencukupi');
                return redirect()->back();
            }

            DB::transaction(function () use ($request, $qty, $product, $id){
                $detailOrder = New DetailOrder();
                $detailOrder->order_id = $request->input('order_id');
                $detailOrder->produk_id = $product->id;
                $detailOrder->qty = $qty;

                $subTotal = intval($qty) * intval($product->harga);

                $detailOrder->sub_total = $subTotal;
                $detailOrder->save();

                /*hitung ulang total qty dan total harga jual*/
                $order = Order::withSum('detailOrders', 'sub_total')
                    ->withSum('detailOrders', 'qty')
                    ->findOrFail($id);
                $order->total_qty = $order->detail_orders_sum_qty;
                $order->total_harga_jual = $order->detail_orders_sum_sub_total;
                $order->save();

                /*update stok produk*/
                $stok = (int) $product->stok - (int) $qty;
                $product->stok = $stok;
                $product->save();
            });

            alert()->success('Success', 'Produk penjualan berhasil ditambah');
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data Error');
        }

        return redirect()->back();
    }

    public function delete(Request $request, $id)
    {
        try {
            DB::transaction(function () use ($request, $id){
                $orderId = $request->input('order_id');
                $order = Order::withCount('detailOrders')
                    ->findOrFail($orderId);

                $detailOrder = DetailOrder::findOrFail($id);

                $product = Product::findOrFail($detailOrder->produk_id);
                $stok = $detailOrder->qty + $product->stok;

                $product->stok = $stok;
                $product->save();

                $detailOrder->delete();

                if ($order->detail_orders_count <= 1) {
                    $order->delete();

                    alert()->success('Success', 'Penjualan berhasil dihapus');

                    return to_route('order.index');
                }else{


                    alert()->success('Success', 'Detail Order Berhasil dihapus');

                    return redirect()->back();
                }
            });
        }catch (\Throwable $throwable){
            Log::error($throwable->getMessage());
            alert()->error('Oops', 'Data error');

            return redirect()->back();
        }
    }
}
