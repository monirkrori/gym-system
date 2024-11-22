<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF; // مكتبة تصدير PDF
use Maatwebsite\Excel\Facades\Excel; // مكتبة تصدير Excel
use App\Exports\ReportsExport; // ملف التصدير لـ Excel
use Illuminate\Support\Facades\Storage; // تخزين الرسومات البيانية كصور

class ReportController extends Controller
{
    /**
     * عرض صفحة التقارير
     */
    public function index()
    {
        // بيانات الإيرادات
        $revenueData = [
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو'],
            'values' => [1000, 1500, 2000, 2500, 3000],
        ];

        // بيانات التسجيلات الشهرية
        $registrationData = [
            'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو'],
            'values' => [20, 35, 50, 65, 80],
        ];

        // بيانات أداء الحصص
        $classPerformanceData = [
            'labels' => ['حصص A', 'حصص B', 'حصص C'],
            'values' => [85, 75, 90],
        ];

        // عرض الصفحة مع البيانات
        return view('reports.index', compact('revenueData', 'registrationData', 'classPerformanceData'));
    }

    /**
     * تصدير التقرير الكامل إلى PDF أو Excel
     */
    public function exportReport($type)
    {
        if ($type == 'pdf') {
            $data = $this->getReportData(); // جلب بيانات التقرير
            $pdf = PDF::loadView('reports.pdf', $data);
            return $pdf->download('report.pdf');
        } elseif ($type == 'excel') {
            return Excel::download(new ReportsExport, 'report.xlsx');
        }

        return redirect()->back()->withErrors('نوع التصدير غير مدعوم.');
    }

    /**
     * تصدير رسم بياني كصورة PNG
     */
    public function exportChart(Request $request)
    {
        $chartType = $request->query('chart');
        $validCharts = ['revenue', 'registrations', 'classPerformance'];

        if (!in_array($chartType, $validCharts)) {
            return redirect()->back()->withErrors('الرسم البياني المطلوب غير موجود.');
        }

        // هنا يمكن حفظ الرسم البياني كصورة باستخدام مكتبة مثل chartjs-node
        // ولأغراض بسيطة، سنعيد محاكاة اسم الصورة المطلوب
        $imagePath = "charts/{$chartType}.png";
        return response()->download(storage_path("app/{$imagePath}"));
    }

    /**
     * جلب بيانات التقرير
     */
    private function getReportData()
    {
        return [
            'revenueData' => [
                'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو'],
                'values' => [1000, 1500, 2000, 2500, 3000],
            ],
            'registrationData' => [
                'labels' => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو'],
                'values' => [20, 35, 50, 65, 80],
            ],
            'classPerformanceData' => [
                'labels' => ['حصص A', 'حصص B', 'حصص C'],
                'values' => [85, 75, 90],
            ],
        ];
    }
}
