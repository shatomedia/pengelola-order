<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary td { border: none; padding: 4px 6px; }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <h3>Laporan Keuangan</h3>
    <p>Periode: {{ $date }}</p>

    <table class="summary">
        <tr>
            <td>Total Pemasukan</td>
            <td class="text-success">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran</td>
            <td class="text-danger">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Saldo</strong></td>
            <td><strong>Rp {{ number_format($saldo, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Sumber/Keterangan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemasukans as $pemasukan)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($pemasukan->tanggal)->isoFormat('DD MMM YYYY') }}</td>
                    <td>Pemasukan</td>
                    <td>{{ optional($pemasukan->kategori)->nama_kategori }}</td>
                    <td>{{ $pemasukan->sumber }}</td>
                    <td>Rp {{ number_format($pemasukan->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @foreach ($pengeluarans as $pengeluaran)
                <tr>
                    <td>{{ \Illuminate\Support\Carbon::parse($pengeluaran->tanggal)->isoFormat('DD MMM YYYY') }}</td>
                    <td>Pengeluaran</td>
                    <td>{{ optional($pengeluaran->kategori)->nama_kategori }}</td>
                    <td>{{ $pengeluaran->keterangan }}</td>
                    <td>Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
