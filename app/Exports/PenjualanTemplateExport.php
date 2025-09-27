<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PenjualanTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Get sample data with available barang
        $barangSample = Barang::stokTersedia()->take(3)->get();

        $data = [];

        if ($barangSample->count() > 0) {
            // Add sample rows
            foreach ($barangSample as $index => $barang) {
                $data[] = [
                    $barang->nama_barang,
                    ($index + 1) * 2, // Sample jumlah
                    date('Y-m-d'), // Today's date
                ];
            }
        } else {
            // If no barang available, show example format
            $data[] = [
                'Contoh Nama Barang',
                5,
                date('Y-m-d'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'nama_barang',
            'jumlah_penjualan',
            'tanggal'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as header
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],

            // Style for data rows
            'A2:C100' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ]
            ],

            // Center align for numbers
            'B2:B100' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ],

            // Center align for dates
            'C2:C100' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // nama_barang
            'B' => 18, // jumlah_penjualan
            'C' => 15, // tanggal
        ];
    }
}
