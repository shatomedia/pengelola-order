<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExportOrder implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles

{
    use Exportable;

    public function collection(): Collection
    {
        $data = [];
        // Generate 200 rows of data
        for ($i = 1; $i <= 200; $i++) {
            $data[] = [
                $i, // Automatically generate row numbers
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ];
        }
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'no',
            'status',
            'nama_pembeli',
            'alamat',
            'no_hp',
            'kode_produk',
            'qty',
            'order_via',
            'tgl_order',
            'tgl_kirim',
            'title',
            'background',
            'request',
            'keterangan'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }
}
