<?php

namespace App\Exports;

use App\Models\UserMembership;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UserMembershipsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return UserMembership::with(['user', 'package'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'اسم المستخدم',
            'البريد الإلكتروني',
            'نوع الباقة',
            'الحالة',
            'تاريخ الإنشاء',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $status = $row->status === 'active' ? 'نشط' : 'منتهي';

        return [
            $row->id,
            $row->user ? $row->user->name : 'غير متوفر',
            $row->user ? $row->user->email : 'غير متوفر',
            $row->package ? $row->package->name : 'غير متوفر',
            $status,
            $row->created_at->format('Y-m-d'),
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        // تنسيق العناوين
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E4E4E4',
                ],
            ],
        ]);

        // تنسيق عام للجدول
        $sheet->getStyle('A1:F' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // ضبط عرض الأعمدة
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(35);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);

        return $sheet;
    }
}
