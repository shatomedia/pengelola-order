<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TemplateExportOrder implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles

{

    use Exportable;
    
    // public function query()
    // {
        
    //    return Order::query()->where('id', 0); 
    // }

    public function collection()
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
            'order_via',
            'tgl_order',
            'tgl_kirim',
            'title',
            'background',
            'request',
            'keterangan'
        ];
    }

    // public function map($row): array
    // {
    //     return [
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         '',
    //         ''
            
    //     ];
    // }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }
}
