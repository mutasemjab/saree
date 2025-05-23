<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class NoteVoucherExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Return a sample row for the template
        return new Collection([
            [
                'منتج مثال',   // Product Name in Arabic
                1,              // Unit ID
                10,             // Quantity
                100.00,         // Purchasing Price
                'ملاحظة المنتج', // Note
                'Bin-A1',       // Bin Number
                'SN123456',     // Serial Number
                date('Y-m-d', strtotime('+1 year')), // Expiry Date
            ],
            // Add more sample rows if needed
        ]);
    }

    public function headings(): array
    {
        return [
            'product_name_ar',  // Product Name in Arabic
            'unit_id',          // Unit ID
            'quantity',         // Quantity
            'purchasing_price', // Purchasing Price
            'note',             // Note
            'bin_number',       // Bin Number
            'serial_number',    // Serial Number
            'expiry_date',      // Expiry Date
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'DDDDDD']]],
        ];
    }
}
