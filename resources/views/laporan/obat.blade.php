<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Obat</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Obat</h1>

    <h2>List Obat</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Harga Per-Unit</th>
                <th>Harga Keseluruhan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPerUnit = 0; // Total harga per unit
                $totalKeseluruhan = 0; // Total jika semua barang terjual
            @endphp

            @foreach ($medicines as $obat)
                @php
                    $subtotal = $obat->stok * $obat->harga; // Harga keseluruhan per obat
                    $totalPerUnit += $obat->harga; // Akumulasi harga per unit
                    $totalKeseluruhan += $subtotal; // Akumulasi total harga keseluruhan
                @endphp
                <tr>
                    <td>{{ $obat->id }}</td>
                    <td>{{ $obat->created_at->format('d-m-Y') }}</td>
                    <td>{{ $obat->nama }}</td>
                    <td>{{ $obat->category->name }}</td>
                    <td>{{ $obat->stok }}</td>
                    <td>Rp. {{ number_format($obat->harga, 2) }}</td>
                    <td>Rp. {{ number_format($subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" style="text-align: left;">Total Harga</th>

                <th colspan="1">Rp. {{ number_format($totalPerUnit, 2) }}</th>
                <th colspan="1">Rp. {{ number_format($totalKeseluruhan, 2) }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
