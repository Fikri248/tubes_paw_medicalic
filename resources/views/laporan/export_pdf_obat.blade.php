<!DOCTYPE html>
<html>

<head>
    <title>Laporan Daftar Obat - Apotek Medicalic</title>
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

        .stok-warning {
            color: #e74c3c;
            font-weight: bold;
        }

        .stok-ok {
            color: #27ae60;
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
                    <span class="font-bold">LAPORAN DAFTAR OBAT</span>
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
                <th style="width: 5%; text-align: center;">No.</th>
                <th style="width: 20%" class="text-center">Nama Obat</th>
                <th style="width: 15%" class="text-center">Kategori</th>
                <th style="width: 10%" class="text-center">Jenis</th>
                <th style="width: 10%" class="text-center">Stok Awal</th>
                <th style="width: 10%" class="text-center">Stok Sisa</th>
                <th style="width: 15%" class="text-center">Harga</th>
                <th style="width: 15%" class="text-center">Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @php $totalObat = 0; @endphp
            @foreach ($obats as $index => $obat)
                @php $totalObat += $obat->stok_sisa; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $obat->nama }}</td>
                    <td class="text-center">{{ $obat->category->name ?? '-' }}</td>
                    <td class="text-center">{{ $obat->jenis }}</td>
                    <td class="text-center">{{ $obat->stok_awal }}</td>
                    <td class="text-center {{ $obat->stok_sisa < 10 ? 'stok-warning' : 'stok-ok' }}">
                        {{ $obat->stok_sisa }}
                    </td>
                    <td class="text-center">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                    <td class="text-center" style="word-wrap: break-word;">{{ $obat->deskripsi }}</td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="text-right font-bold" style="border: none;">Total Stok Obat:</td>
                <td class="text-center font-bold" style="border: none;">{{ $totalObat }}</td>
                <td colspan="2" style="border: none;"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara digital dan tidak memerlukan tanda tangan.</p>
        <p>© {{ date('Y') }} Apotek Medicalic - Semua hak dilindungi.</p>
    </div>
</body>

</html>
