<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .container { width: 100%; padding: 20px; }
        h2 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Laporan Dashboard</h2>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::now()->format('d M Y') }}</p>

        <h3>Ringkasan Transaksi</h3>
        <table>
            <tr>
                <th>Bulan Ini</th>
                <th>Bulan Lalu</th>
                <th>Perubahan (%)</th>
            </tr>
            <tr>
                <td>{{ $transaksiBulanIni }}</td>
                <td>{{ $transaksiBulanLalu }}</td>
                <td>{{ number_format($percentTransaksi, 2) }}%</td>
            </tr>
        </table>

        <h3>Pendapatan</h3>
        <table>
            <tr>
                <th>Bulan Ini</th>
                <th>Bulan Lalu</th>
                <th>Perubahan (%)</th>
            </tr>
            <tr>
                <td>Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pendapatanBulanLalu, 0, ',', '.') }}</td>
                <td>{{ number_format($percentPendapatan, 2) }}%</td>
            </tr>
        </table>

        <h3>Jumlah Member & Outlet</h3>
        <table>
            <tr>
                <th>Jumlah Member</th>
                <th>Jumlah Outlet</th>
            </tr>
            <tr>
                <td>{{ $jumlahMember }}</td>
                <td>{{ $jumlahOutlet }}</td>
            </tr>
        </table>

        <h3>Status Transaksi</h3>
        <table>
            <tr>
                <th>Baru</th>
                <th>Proses</th>
                <th>Selesai</th>
                <th>Diambil</th>
            </tr>
            <tr>
                <td>{{ $statusTransaksi['baru'] }}</td>
                <td>{{ $statusTransaksi['proses'] }}</td>
                <td>{{ $statusTransaksi['selesai'] }}</td>
                <td>{{ $statusTransaksi['diambil'] }}</td>
            </tr>
        </table>

        <h3>Paket Paling Populer</h3>
        <p><strong>{{ $paketPalingBanyak->nama_paket ?? 'Tidak ada data' }}</strong> ({{ $paketPalingBanyak->total_qty ?? 0 }} kali dipesan)</p>

        <p style="text-align: center; margin-top: 20px;">Dicetak oleh sistem</p>
    </div>
</body>
</html>
