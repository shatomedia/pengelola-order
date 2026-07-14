<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->no_faktur }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #212529;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #344767;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .invoice-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .invoice-brand img {
            height: 48px;
            width: auto;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 22px;
            color: #344767;
        }
        .invoice-header p { margin: 2px 0; font-size: 13px; color: #67748e; }
        .invoice-brand div { max-width: 320px; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { margin: 0; font-size: 26px; letter-spacing: 2px; color: #344767; }
        .invoice-title p { margin: 4px 0; font-size: 13px; }
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
        }
        .badge-lunas { background: #82d616; }
        .badge-dp { background: #fbcf33; color: #333; }
        .badge-belum { background: #ea0606; }
        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
            gap: 24px;
        }
        .info-grid .box { flex: 1; }
        .info-grid h4 { margin: 0 0 6px; font-size: 12px; text-transform: uppercase; color: #67748e; }
        .info-grid p { margin: 2px 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        thead th {
            background: #344767;
            color: #fff;
            text-align: left;
            padding: 10px 8px;
            font-size: 12px;
            text-transform: uppercase;
        }
        tbody td { padding: 10px 8px; border-bottom: 1px solid #e9ecef; font-size: 14px; }
        tbody td.num, thead th.num { text-align: right; }
        tfoot td { padding: 8px; font-size: 14px; }
        tfoot .label { text-align: right; font-weight: 600; }
        tfoot .value { text-align: right; width: 160px; }
        tfoot .grand-total { font-size: 18px; font-weight: 700; color: #344767; border-top: 2px solid #344767; }
        .notes { font-size: 13px; color: #67748e; margin-top: 24px; }
        .print-bar { text-align: right; margin-bottom: 16px; }
        .print-bar button {
            background: #344767;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        @media print {
            .print-bar { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="print-bar">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="invoice-header">
        <div class="invoice-brand">
            <img src="{{ asset('img/sidebar-logo.png') }}" alt="Shatomedia">
            <div>
                <p>shatomedia.com</p>
                <p>Jl. Wates KM.11 Perum GKP Blok BC.2/11, Sedayu, Kabupaten Bantul, Daerah Istimewa Yogyakarta, 55752</p>
            </div>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>{{ $order->no_faktur }}</strong></p>
            <p>
                @if($order->payment_status == 'Lunas')
                    <span class="badge badge-lunas">Lunas</span>
                @elseif($order->payment_status == 'DP')
                    <span class="badge badge-dp">DP</span>
                @else
                    <span class="badge badge-belum">Belum Bayar</span>
                @endif
            </p>
        </div>
    </div>

    <div class="info-grid">
        <div class="box">
            <h4>Ditagihkan Kepada</h4>
            <p><strong>{{ $order->nama_pembeli }}</strong></p>
            <p>{{ $order->alamat }}</p>
            <p>{{ $order->no_hp }}</p>
        </div>
        <div class="box">
            <h4>Detail Pesanan</h4>
            <p>Tgl Order: {{ $order->tgl_order }}</p>
            <p>Tgl Kirim: {{ $order->tgl_kirim }}</p>
            <p>Order Via: {{ $order->order_via }}</p>
            <p>Status: {{ $order->status }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th class="num">Qty</th>
                <th class="num">Harga Satuan</th>
                <th class="num">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->detailOrders as $detailOrder)
                <tr>
                    <td>{{ optional($detailOrder->produk)->nama }}</td>
                    <td class="num">{{ $detailOrder->qty }} {{ optional($detailOrder->produk)->satuan }}</td>
                    <td class="num">Rp {{ number_format($detailOrder->qty > 0 ? $detailOrder->sub_total / $detailOrder->qty : 0, 0, ',', '.') }}</td>
                    <td class="num">Rp {{ number_format($detailOrder->sub_total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="label">Total Tagihan</td>
                <td class="value grand-total">Rp {{ number_format($order->total_harga_jual, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="label">Sudah Dibayar</td>
                <td class="value">Rp {{ number_format($order->jumlah_dibayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="label">Sisa Tagihan</td>
                <td class="value">Rp {{ number_format(max($order->total_harga_jual - $order->jumlah_dibayar, 0), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    @if($order->keterangan)
        <div class="notes">
            <strong>Keterangan:</strong> {{ $order->keterangan }}
        </div>
    @endif

    <div class="notes">
        Terima kasih atas pesanan Anda.
    </div>
</body>
</html>
