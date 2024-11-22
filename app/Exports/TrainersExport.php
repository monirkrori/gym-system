<?php

namespace App\Exports;

use App\Models\Trainer;
use Maatwebsite\Excel\Concerns\FromCollection;

class TrainersExport implements FromCollection
{
    public function collection()
    {
        return Trainer::all(); // جلب جميع المدربين
    }
}
