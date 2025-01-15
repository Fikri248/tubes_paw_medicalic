<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi - Apotek Medicalic</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            margin: 30px;
        }

        .header {
            padding: 20px 0;
            border-bottom: 2px solid #4a90e2;
            margin-bottom: 20px;
        }

        .company-info {
            text-align: center;
        }

        .company-info h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .company-info p {
            color: #7f8c8d;
            font-size: 12px;
            line-height: 1.4;
        }

        .document-meta {
            margin: 20px 0;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .document-meta p {
            font-size: 12px;
            color: #7f8c8d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }

        thead {
            background-color: #4a90e2;
            color: white;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
        }

        th {
            font-weight: bold;
            white-space: nowrap;
            border-color: #3780d6;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 30px;
            right: 30px;
            padding-top: 10px;
            font-size: 10px;
            text-align: center;
            color: #7f8c8d;
            border-top: 1px solid #e0e0e0;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <h1>APOTEK MEDICALIC</h1>
            <p>Jl. Contoh No. 123, Kota, Provinsi, 12345</p>
            <p>Telp: (021) 123-4567 | Email: info@medicalic.com</p>
        </div>
    </div>

    <div class="document-meta">
        <table style="margin: 0;">
            <tr>
                <td style="border: none; padding: 4px 0; width: 50%;">
                    <span class="font-bold">LAPORAN TRANSAKSI</span>
                </td>
                <td style="border: none; padding: 4px 0; text-align: right;">
                    Tanggal Cetak: {{ date('d/m/Y H:i', strtotime('+7 hours')) }} WIB
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%" class="text-center">No.</th>
                <th style="width: 25%" class="text-center">Nama Obat</th>
                <th style="width: 15%" class="text-center">Jumlah Obat</th>
                <th style="width: 15%" class="text-center">Total Jumlah Obat</th>
                <th style="width: 20%" class="text-center">Total Harga</th>
                <th style="width: 20%" class="text-center">Waktu Pembelian</th>
            </tr>
        </thead>
        <tbody>
            @php $totalTransaksi = 0; @endphp
            @foreach ($data as $row)
                @php $totalTransaksi += $row['total_harga']; @endphp
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td>{{ $row['nama_obat'] }}</td>
                    <td class="text-center">{{ $row['jumlah_obat'] }}</td>
                    <td class="text-center">{{ $row['total_jumlah_obat'] }}</td>
                    <td class="text-center">Rp {{ number_format($row['total_harga'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row['waktu_pembelian'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right font-bold" style="border: none;">Total Pendapatan:</td>
                <td class="text-center font-bold" style="border: none;">Rp
                    {{ number_format($totalTransaksi, 0, ',', '.') }}</td>
                <td style="border: none;"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara digital dan tidak memerlukan tanda tangan.</p>
        <p>© {{ date('Y') }} Apotek Medicalic - Semua hak dilindungi.</p>
    </div>
</body>

</html>
