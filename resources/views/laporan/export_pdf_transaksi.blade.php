<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>Laporan Transaksi</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Obat</th>
                <th>Jumlah Obat</th>
                <th>Total Jumlah Obat</th>
                <th>Total Harga</th>
                <th>Waktu Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['nama_obat'] }}</td>
                <td>{{ $row['jumlah_obat'] }}</td>
                <td>{{ $row['total_jumlah_obat'] }}</td>
                <td>Rp {{ number_format($row['total_harga'], 0, ',', '.') }}</td>
                <td>{{ $row['waktu_pembelian'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
