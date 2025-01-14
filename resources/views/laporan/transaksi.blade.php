<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
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
    <h1>Laporan Transaksi</h1>

    <h2>List Transaksi</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->created_at->format('d-m-Y') }}</td>
                    <td>{{ $transaction->nama }}</td>
                    <td>{{ $transaction->jenis }}</td>
                    <td>{{ $transaction->jumlah }}</td>
                    <td>Rp. {{ number_format($transaction->harga, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Total</th>
                <th>Rp. {{ number_format($totalPrice, 2) }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>
