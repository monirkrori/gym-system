
@extends('layouts.dashboard')

@section('title', 'تقارير النظام')
@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a6cf7;
            --success-color: #34d399;
            --danger-color: #f43f5e;
            --info-color: #38bdf8;
            --bg-light: #f4f6f9;
            --text-dark: #1f2937;
        }

        body {
            background-color: var(--bg-light);
            font-family: 'Cairo', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container-fluid {
            padding: 0 30px;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), #7c3aed);
            padding: 20px 0;
            margin-bottom: 30px;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .page-header h1 {
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .stats-card h6 {
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stats-card h3 {
            color: var(--primary-color);
            font-weight: 700;
        }

        .stats-card.card-success h3 {
            color: var(--success-color);
        }

        .stats-card.card-danger h3 {
            color: var(--danger-color);
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        /* Export Buttons Styling */
        .export-buttons {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .export-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .export-btn i {
            font-size: 1.2em;
        }

        .export-btn-excel {
            background-color: var(--success-color);
            color: white;
        }

        .export-btn-pdf {
            background-color: var(--danger-color);
            color: white;
        }

        .export-btn-print {
            background-color: var(--info-color);
            color: white;
        }

        .export-btn:hover {
            opacity: 0.9;
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        .table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        }

        .table thead {
            background-color: var(--bg-light);
        }

        .badge-active {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-expired {
            background-color: rgba(244, 63, 94, 0.1);
            color: var(--danger-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .export-buttons {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="page-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-white">
                    <i class="bi bi-graph-up-arrow me-2"></i> تقارير النظام
                </h1>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stats-card card-success text-center p-4">
                <h6>الإيرادات الشهرية</h6>
                <h3 class="text-success">{{ number_format($monthlyRevenue, 2) }}$</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card text-center p-4">
                <h6>عدد الأعضاء النشطين</h6>
                <h3>{{ $activeMembers }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card card-danger text-center p-4">
                <h6>عدد العضويات المنتهية</h6>
                <h3 class="text-danger">{{ $expiredMemberships }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card text-center p-4">
                <h6>إجمالي المدربين</h6>
                <h3>{{ $trainersCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stats-card p-4">
                <h6 class="text-primary">إيرادات الأشهر السابقة</h6>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stats-card p-4">
                <h6 class="text-primary">الأعضاء حسب الحالة</h6>
                <div class="chart-container">
                    <canvas id="membersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <button id="export-excel" class="btn export-btn export-btn-excel">
            <i class="bi bi-file-earmark-excel"></i> تصدير Excel
        </button>
        <button id="export-pdf" class="btn export-btn export-btn-pdf">
            <i class="bi bi-file-earmark-pdf"></i> تصدير PDF
        </button>
        <button id="export-print" class="btn export-btn export-btn-print">
            <i class="bi bi-printer"></i> طباعة
        </button>
    </div>

    <!-- Members Table -->
    <div class="table-responsive">
        <table id="membersTable" class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الباقة</th>
                    <th>الحالة</th>
                    <th>تاريخ الانضمام</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $member->name }}</td>
                        <td>{{ $member->membership_plan }}</td>
                        <td>
                            <span class="badge {{ $member->status == 'active' ? 'badge-active' : 'badge-expired' }}">
                                {{ $member->status == 'active' ? 'نشط' : 'منتهي' }}
                            </span>
                        </td>
                        <td>{{ $member->join_date->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $members->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($revenueMonths),
            datasets: [{
                label: 'الإيرادات',
                data: @json($monthlyRevenueData),
                borderColor: '#4a6cf7',
                borderWidth: 3,
                fill: true,
                backgroundColor: 'rgba(74, 108, 247, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Members Chart
    const membersCtx = document.getElementById('membersChart').getContext('2d');
    new Chart(membersCtx, {
        type: 'doughnut',
        data: {
            labels: ['نشط', 'منتهي'],
            datasets: [{
                data: @json([$activeMembers, $expiredMemberships]),
                backgroundColor: ['#34d399', '#f43f5e']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    // Export Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const membersTable = document.getElementById('membersTable');

        // Excel Export
        document.getElementById('export-excel').addEventListener('click', function() {
            const wb = XLSX.utils.table_to_book(membersTable);
            XLSX.writeFile(wb, 'تقرير_الأعضاء_' + new Date().toLocaleDateString() + '.xlsx');
        });

        // PDF Export
        document.getElementById('export-pdf').addEventListener('click', function() {
            const element = membersTable;
            const opt = {
                margin: 10,
                filename: 'تقرير_الأعضاء_' + new Date().toLocaleDateString() + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            };

            html2pdf().set(opt).from(element).save();
        });

        // Print Export
        document.getElementById('export-print').addEventListener('click', function() {
            const printContents = membersTable.outerHTML;
            const originalContents = document.body.innerHTML;

            document.body.innerHTML = `
                <html>
                    <head>
                        <title>تقرير الأعضاء</title>
                        <style>
                            body { direction: rtl; font-family: Arial, sans-serif; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
                            th { background-color: #f2f2f2; }
                        </style>
                    </head>
                    <body>
                        <h2>تقرير الأعضاء</h2>
                        ${printContents}
                    </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    });
</script>
@endpush
