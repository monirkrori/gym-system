<?php

namespace App\Http\Controllers;

use App\Exports\TrainersExport;
use Illuminate\Http\Request;
use App\Models\Trainer;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class TrainerController extends Controller
{
    // عرض قائمة المدربين
    public function index()
    {
        $trainers = Trainer::all();
        return view('trainers.index', compact('trainers'));
    }

    // تصدير المدربين
    public function export($type)
    {
        switch ($type) {
            case 'pdf':
                return $this->exportAsPdf();
            case 'excel':
                return $this->exportAsExcel();
            case 'csv':
                return $this->exportAsCsv();
            default:
                abort(404, 'نوع التصدير غير مدعوم');
        }
    }

    // تصدير كـ PDF
    private function exportAsPdf()
    {
        $trainers = Trainer::all();
        $pdf = Pdf::loadView('trainers.export_pdf', compact('trainers')); // قم بإنشاء صفحة HTML للتصدير
        return $pdf->download('trainers.pdf');
    }

    // تصدير كـ Excel
    private function exportAsExcel()
    {

        return Excel::download(new TrainersExport, 'trainers.xlsx'); // استخدم Export Class
    }

    // تصدير كـ CSV
    private function exportAsCsv()
    {
        return Excel::download(new TrainersExport, 'trainers.csv'); // استخدم نفس Export Class
    }
}
