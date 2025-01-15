<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class TransaksiExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    WithColumnWidths,
    WithCustomStartCell,
    WithEvents
{
    private $row = 0;
    private $totalHargaPembelian = 0; 

    public function __construct()
    {
        $this->row = 0;
        $this->totalHargaPembelian = 0;
    }

    public function query()
    {
        return Order::with(['transaksi.obat'])->select(['id', 'total_harga', 'created_at']);
    }

    public function map($order): array
    {
        $this->row++;

        $groupedObat = $order->transaksi->groupBy('obat_id')->map(function ($transaksis) {
            $namaObat = $transaksis->first()->obat->nama;
            $totalJumlah = $transaksis->sum('jumlah');
            return [
                'nama_obat' => $namaObat,
                'jumlah_obat' => $totalJumlah,
            ];
        });

        $namaObat = $groupedObat->pluck('nama_obat')->implode(', ');
        $jumlahObat = $groupedObat->pluck('jumlah_obat')->implode(', ');

        $totalJumlahObat = $order->transaksi->sum('jumlah');

        // total harga pembelian
        $this->totalHargaPembelian += $order->total_harga;

        return [
            $this->row,
            $namaObat,
            $jumlahObat,
            $totalJumlahObat,
            'Rp ' . number_format($order->total_harga, 0, ',', '.'),
            $order->created_at->addHours(7)->format('d/m/Y, H.i'),
        ];
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Obat',
            'Jumlah Obat',
            'Total Jumlah Obat',
            'Total Harga',
            'Waktu Pembelian',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // header perusahaan
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        $sheet->setCellValue('A1', 'APOTEK MEDICALIC');
        $sheet->setCellValue('A2', 'Jl. Contoh No. 123, Kota, Provinsi, 12345');
        $sheet->setCellValue('A3', 'Telp: (021) 123-4567 | Email: info@medicalic.com');

        // judul dokumen dan tanggal
        $sheet->mergeCells('A5:C5');
        $sheet->setCellValue('A5', 'LAPORAN TRANSAKSI OBAT');
        $waktuWIB = date('d/m/Y H:i', strtotime('+7 hours'));
        $sheet->setCellValue('F5', 'Tanggal Cetak: ' . $waktuWIB . ' WIB');

        $headerStyle = [
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        $subHeaderStyle = [
            'font' => ['size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        $titleStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]
        ];

        $dateStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'font' => ['size' => 11]
        ];

        // style untuk cell F5 (tanggal cetak)
        $sheet->getStyle('F5')->applyFromArray($dateStyle);

        return [
            1 => $headerStyle,
            2 => $subHeaderStyle,
            3 => $subHeaderStyle,
            5 => $titleStyle,
            7 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 30, // Nama Obat
            'C' => 20, // Jumlah Obat
            'D' => 20, // Total Jumlah Obat
            'E' => 20, // Total Harga
            'F' => 20, // Waktu Pembelian
        ];
    }

    public function title(): string
    {
        return 'Transaksi Obat';
    }

    public function startCell(): string
    {
        return 'A7';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'F';

                // border tabel
                $sheet->getStyle('A7:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'DDDDDD']
                        ]
                    ]
                ]);

                // kolom-kolom tertentu
                $sheet->getStyle('A7:A' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('C7:E' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // total harga pembelian
                $totalRow = $lastRow + 1;
                $sheet->mergeCells('A' . $totalRow . ':D' . $totalRow);
                $sheet->setCellValue('A' . $totalRow, 'Total Harga Pembelian Obat:');
                $sheet->setCellValue('E' . $totalRow, 'Rp ' . number_format($this->totalHargaPembelian, 0, ',', '.'));
                $sheet->getStyle('A' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('E' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $totalRow . ':E' . $totalRow)->getFont()->setBold(true);
            }
        ];
    }
}
