<?php

namespace App\Exports;

use App\Models\Obat;
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
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class ObatExport implements
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
    private $totalStok = 0;

    public function __construct()
    {
        $this->row = 0;
        $this->totalStok = 0;
    }

    public function query()
    {
        return Obat::with('category', 'transaksis')
            ->select(['id', 'nama', 'category_id', 'jenis', 'stok_awal', 'stok_sisa', 'harga', 'deskripsi']);
    }

    public function map($obat): array
    {
        $this->totalStok += $obat->stok_sisa;
        $this->row++;

        return [
            $this->row, 
            $obat->nama,
            $obat->category->name ?? '-',
            $obat->jenis,
            $obat->stok_awal,
            $obat->stok_sisa,
            'Rp ' . number_format($obat->harga, 0, ',', '.'),
            $obat->deskripsi
        ];
    }

    public function headings(): array
    {
        return [
            'No.',
            'Nama Obat',
            'Kategori',
            'Jenis',
            'Stok Awal',
            'Stok Sisa',
            'Harga',
            'Deskripsi',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // header perusahaan
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $sheet->setCellValue('A1', 'APOTEK MEDICALIC');
        $sheet->setCellValue('A2', 'Jl. Contoh No. 123, Kota, Provinsi, 12345');
        $sheet->setCellValue('A3', 'Telp: (021) 123-4567 | Email: info@medicalic.com');

        // judul dokumen dan tanggal
        $sheet->mergeCells('A5:D5');
        $sheet->setCellValue('A5', 'LAPORAN DAFTAR OBAT');
        $waktuWIB = date('d/m/Y H:i', strtotime('+7 hours'));
        $sheet->setCellValue('H5', 'Tanggal Cetak: ' . $waktuWIB . ' WIB');

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

        // style untuk cell H5 (tanggal cetak)
        $sheet->getStyle('H5')->applyFromArray($dateStyle);

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
            'B' => 25, // Nama Obat
            'C' => 20, // Kategori
            'D' => 15, // Jenis
            'E' => 12, // Stok Awal
            'F' => 12, // Stok Sisa
            'G' => 20, // Harga
            'H' => 30, // Deskripsi
        ];
    }

    public function title(): string
    {
        return 'Daftar Obat';
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
                $lastColumn = 'H';

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
                $sheet->getStyle('C7:G' . $lastRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // formatting untuk stok rendah
                $conditionalStyles = [];
                $conditional = new Conditional();
                $conditional->setConditionType(Conditional::CONDITION_CELLIS);
                $conditional->setOperatorType(Conditional::OPERATOR_LESSTHAN);
                $conditional->addCondition(10);
                $conditional->getStyle()->getFont()->getColor()->setRGB('E74C3C');
                $conditional->getStyle()->getFont()->setBold(true);
                $conditionalStyles[] = $conditional;

                $sheet->getStyle('F8:F' . $lastRow)
                    ->setConditionalStyles($conditionalStyles);

                // Total Stok
                $totalRow = $lastRow + 1;
                $sheet->mergeCells('A' . $totalRow . ':E' . $totalRow);
                $sheet->setCellValue('A' . $totalRow, 'Total Stok Obat:');
                $sheet->setCellValue('F' . $totalRow, $this->totalStok);
                $sheet->getStyle('A' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                $sheet->getStyle('F' . $totalRow)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A' . $totalRow . ':F' . $totalRow)->getFont()->setBold(true);
            }
        ];
    }
}
