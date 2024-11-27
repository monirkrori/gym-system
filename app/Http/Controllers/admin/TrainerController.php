<?php

namespace App\Http\Controllers\admin;

use App\Exports\TrainersExport;
use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use function abort;
use function view;

class TrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::all();
        return view('trainers.index', compact('trainers'));
    }

    public function export($type)
    {
        switch ($type) {
            case 'pdf':
                return $this->exportAsPdf();
            case 'excel':
                return $this->exportAsExcel();
                default:
                abort(404, 'نوع التصدير غير مدعوم');
        }
    }

    private function exportAsPdf()
    {
        $trainers = Trainer::all();
        $pdf = Pdf::loadView('trainers.pdf', compact('trainers'));
        return $pdf->download('trainers.pdf');
    }

    private function exportAsExcel()
    {

        return Excel::download(new TrainersExport, 'trainers.xlsx');
    }

}
