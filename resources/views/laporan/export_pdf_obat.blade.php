<!DOCTYPE html>
<html>
<head>
    <title>Laporan Daftar Obat</title>
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
    <h2>Laporan Daftar Obat</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Stok Awal</th>
                <th>Stok Sisa</th>
                <th>Harga</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($obats as $index => $obat)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $obat->nama }}</td>
                <td>{{ $obat->category->name ?? '-' }}</td>
                <td>{{ $obat->jenis }}</td>
                <td>{{ $obat->stok_awal }}</td>
                <td>{{ $obat->stok_sisa }}</td>
                <td>Rp {{ number_format($obat->harga, 2, ',', '.') }}</td>
                <td>{{ $obat->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
