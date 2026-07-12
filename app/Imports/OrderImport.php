<?php

namespace App\Imports;

use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection): void
    {
        DB::transaction(function () use ($collection){
            foreach ($collection as $row)
            {
                // jika sudah ada kode produk maka diambil data pertama, jika kosong maka buat baru
                $produk = Product::kodeProduk($row['kode_produk'])
                    ->firstOrNew();
                $produk->nama = Str::title(str_replace('-', ' ', $row['kode_produk']));
                $produk->kode_produk = $row['kode_produk'];
                $produk->kategori_id = random_int(1,2);
                $produk->harga = 1000000;
                $produk->deskripsi = 'Isi dengan deskripsi produk';
                $produk->stok = 100;
                $produk->satuan = 'Item';
                $produk->save();

                $tanggalOrder = Date::excelToDateTimeObject($row['tgl_order']);
                $tanggalKirim = $row['tgl_kirim'] ? Date::excelToDateTimeObject($row['tgl_kirim']) : null;

                $order = Order::whereDate('tgl_kirim', $tanggalKirim)
                    ->where('nama_pembeli', $row['nama_pembeli'])
                    ->firstOrNew();

                $order->status = $row['status'];
                $order->nama_pembeli = $row['nama_pembeli'];
                $order->alamat = $row['alamat'] ?: '-';
                $order->no_hp = $row['no_hp'];
                $order->order_via = $row['order_via'] ?: '-';
                $order->tgl_order = $tanggalOrder;
                $order->tgl_kirim = $tanggalKirim;
                $order->title = $row['title'] ?: '-';
                $order->background = $row['background'];
                $order->request = $row['request'];
                $order->keterangan = $row['keterangan'];
                $order->save();

                $detailOrder = New DetailOrder();
                $detailOrder->order_id = $order->id;
                $detailOrder->produk_id = $produk->id;
                $detailOrder->qty = $row['qty'];

                $subTotal = $detailOrder->qty * $produk->harga;

                $detailOrder->sub_total = $subTotal;
                $detailOrder->save();

                $detailOrderProduk = DetailOrder::orderId($order->id)
                    ->get();
                $sycnQty = Order::find($order->id);
                $sycnQty->total_qty = $detailOrderProduk->sum('qty');
                $sycnQty->total_harga_jual = $detailOrderProduk->sum('sub_total');
                $sycnQty->save();
            }
        });
    }
}
