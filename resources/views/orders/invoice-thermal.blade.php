<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->no_faktur }}</title>
    <style>
        @page {
            size: 100mm 100mm;
            margin: 3mm;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            color: #000;
            width: 94mm;
            margin: 0 auto;
            padding: 0;
        }
        .center { text-align: center; }
        .logo { max-width: 28mm; margin: 0 auto 2mm; display: block; }
        .brand-name { font-size: 13px; font-weight: bold; margin: 0; }
        .tagline, .address { font-size: 9px; margin: 1px 0; }
        hr { border: none; border-top: 1px dashed #000; margin: 6px 0; }
        .row { display: flex; justify-content: space-between; margin: 2px 0; }
        table { width: 100%; border-collapse: collapse; margin: 4px 0; }
        th, td { text-align: left; padding: 2px 0; font-size: 10px; }
        th.num, td.num { text-align: right; }
        tfoot td { font-size: 11px; padding-top: 4px; }
        tfoot .grand { font-weight: bold; font-size: 12px; border-top: 1px dashed #000; padding-top: 4px; }
        .footer-note { text-align: center; font-size: 9px; margin-top: 8px; }
        .print-bar { text-align: center; margin-bottom: 10px; }
        .print-bar button {
            background: #344767;
            color: #fff;
            border: none;
            padding: 6px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-family: Arial, sans-serif;
        }
        @media print {
            .print-bar { display: none; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <a href="{{ route('order.invoice', $order->id) }}" style="margin-right: 8px; color: #344767; font-size: 13px; text-decoration: underline;">Versi A4</a>
        <button onclick="window.print()"><i class="fas fa-print" aria-hidden="true"></i> Cetak / Simpan PDF</button>
    </div>

    <div class="center">
        <img class="logo" src="{{ asset('img/sidebar-logo.png') }}" alt="Shatomedia">
        <p class="tagline">shatomedia.com</p>
        <p class="address">Jl. Wates KM.11 Perum GKP Blok BC.2/11, Sedayu, Kabupaten Bantul, Daerah Istimewa Yogyakarta, 55752</p>
    </div>

    <hr>

    <div class="row"><span>No. Faktur</span><span>{{ $order->no_faktur }}</span></div>
    <div class="row"><span>Tgl Order</span><span>{{ $order->tgl_order }}</span></div>
    <div class="row"><span>Status</span><span>{{ $order->status }}</span></div>
    <div class="row"><span>Bayar</span><span>{{ $order->payment_status }}</span></div>

    <hr>

    <div><strong>{{ $order->nama_pembeli }}</strong></div>
    <div>{{ $order->alamat }}</div>
    <div>{{ $order->no_hp }}</div>

    <hr>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="num">Qty</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->detailOrders as $detailOrder)
                <tr>
                    <td>{{ optional($detailOrder->produk)->nama }}</td>
                    <td class="num">{{ $detailOrder->qty }}</td>
                    <td class="num">{{ number_format($detailOrder->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td class="num grand">{{ number_format($order->total_harga_jual, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Dibayar</td>
                <td class="num">{{ number_format($order->jumlah_dibayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2">Sisa</td>
                <td class="num">{{ number_format(max($order->total_harga_jual - $order->jumlah_dibayar, 0), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <hr>

    <div class="footer-note">Terima kasih atas pesanan Anda</div>
</body>
</html>
