@extends('layouts.dashboard')

@section('title', 'إدارة المدربين')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .stats-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }
        .badge-status {
            padding: 0.5em 1em;
            border-radius: 30px;
        }
        .action-buttons .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            min-width: 160px;
            z-index: 1000;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            padding: 8px 16px;
            color: #333;
            text-decoration: none;
            display: block;
        }

        .dropdown-item:hover {
            background-color: #f1f1f1;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">إدارة المدربين</h1>
            <!-- زر التصدير مع القائمة المنسدلة -->
            <div class="dropdown">
                <button class="btn btn-secondary">
                    <i class="bi bi-file-earmark-earmark me-2"></i> تصدير
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.trainers.export', ['type' => 'excel']) }}">
                            <i class="bi bi-file-earmark-excel me-2 text-success"></i>
                            تصدير Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.trainers.export', ['type' => 'pdf']) }}">
                            <i class="bi bi-file-earmark-pdf me-2 text-danger"></i>
                            تصدير PDF
                        </a>
                    </li>
                </ul>
            </div>

            <div class="d-flex gap-2">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> إضافة مدرب
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">إجمالي المدربين</h6>
                                <h2 class="card-title mb-0">50</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-check-circle fs-4"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">مدربين نشطين</h6>
                                <h2 class="card-title mb-0">30</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card stats-card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                                <i class="bi bi-x-circle fs-4"></i>
                            </div>
                            <div>
                                <h6 class="card-subtitle mb-1 text-muted">مدربين غير نشطين</h6>
                                <h2 class="card-title mb-0">20</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="trainersTable" class="table table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>اسم المدرب</th>
                            <th>البريد الإلكتروني</th>
                            <th>الحالة</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- بيانات وهمية -->
                        @foreach (range(1, 10) as $index)
                            <tr>
                                <td>{{ $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('images/default-avatar.png') }}"
                                             class="rounded-circle me-2"
                                             width="32"
                                             height="32"
                                             alt="صورة المدرب">
                                        <div>
                                            <div class="fw-bold">مدرب {{ $index }}</div>
                                            <div class="small text-muted">مدرب {{ $index }}@example.com</div>
                                        </div>
                                    </div>
                                </td>
                                <td>trainer{{ $index }}@example.com</td>
                                <td>
                                    <span class="badge badge-status {{ $index % 2 === 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $index % 2 === 0 ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>{{ now()->subDays($index)->format('Y-m-d') }}</td>
                                <td>
                                    <div class="action-buttons d-flex gap-1">
                                        <a href="#" class="btn btn-sm btn-info" title="عرض">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning" title="تعديل">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#trainersTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });
        });
    </script>
@endpush
