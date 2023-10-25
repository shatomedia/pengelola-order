<?php

namespace App\Imports;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class OrderImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            // dd($row['status']);
            $produk = Product::where('kode_produk', $row['kode_produk'])->first();
            $tanggalOrder = Date::excelToDateTimeObject($row['tgl_order']);
            $tanggalKirim = Date::excelToDateTimeObject($row['tgl_kirim']);

            $order = New Order();
            $order->status = $row['status'];
            $order->nama_pembeli = $row['nama_pembeli'];
            $order->alamat = $row['alamat'];
            $order->no_hp = $row['no_hp'];
            $order->produk_id = $produk->id;
            $order->order_via = $row['order_via'];
            $order->tgl_order = $tanggalOrder;
            $order->tgl_kirim = $tanggalKirim;
            $order->title = $row['title'];
            $order->background = $row['background'];
            $order->request = $row['request'];
            $order->keterangan = $row['keterangan'];
            $order->save();
            // dd($order);

            

            // Order::create([
            // 'status' => $row['status'],
            // 'nama_pembeli' => $row['nama_pembeli'],
            // 'alamat' => $row['alamat'],
            // 'no_hp' => $row['no_hp'],
            // 'produk_id' => 1,
            // 'order_via' => $row['order_via'],
            // 'tgl_order' => $row['tgl_order'],
            // 'tgl_kirim' => $row['tgl_kirim'],
            // 'title' => $row['title'],
            // 'background' => $row['background'],
            // 'request' => $row['request'],
            // 'keterangan' => $row['keterangan']
            // ]);
        }
    }

    // /**
    //   * @return int
    //  */
    // public function startRow(): int
    // {
    //     return 2;
    // }
}







// class OrderImport implements ToModel, WithStartRow
// {
//     /**
//     * @param array $row
//     *
//     * @return \Illuminate\Database\Eloquent\Model|null
//     */
//     public function model(array $row)
//     {
//         if(!array_filter($row)) {
//             return null;
//         }
        
//         return new Order([
//             'status' => $row['status'],
//             'nama_pembeli' => $row['nama_pembeli'],
//             'alamat' => $row['alamat'],
//             'no_hp' => $row['no_hp'],
//             'kode_produk' => $row['kode_produk'],
//             'order_via' => $row['order_via'],
//             'tgl_order' => $row['tgl_order'],
//             'tgl_kirim' => $row['tgl_kirim'],
//             'title' => $row['title'],
//             'background' => $row['background'],
//             'request' => $row['request'],
//             'keterangan' => $row['keterangan']
//         ]);
//     }

//     /**
//      * @return int
//      */
//     public function startRow(): int
//     {
//         return 2;
//     }

//     // public function rules(): array
//     // {
//     //     return [
//     //         'status' => 'required',
//     //         'nama_pembeli' => 'required',
//     //         'alamat' => 'required',
//     //         'no_hp' => 'required',
//     //         'kode_produk' => 'required',
//     //         'order_via' => 'required',
//     //         'tgl_order' => 'required',
//     //         'tgl_kirim' => 'required',
//     //         'title' => 'required',
//     //         'background' => 'required',
//     //         'request' => 'required',
//     //         'keterangan' => 'required',
//     //     ];
//     // }
// }
