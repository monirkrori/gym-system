@extends('layouts.dashboard')

@section('title', 'إدارة المعدات الرياضية')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }

        body {
            background-color: #f4f7fa;
        }

        .stats-card {
            transition: all 0.4s ease;
            border-radius: 15px;
            overflow: hidden;
            background-size: 200% 200%;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }

        .stats-card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
            transition: background-color 0.3s ease;
        }

        .action-buttons .btn {
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-tools me-2"></i> إدارة المعدات الرياضية
            </h1>
            <a href="{{ route('admin.equipments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة معدة جديدة
            </a>
        </div>

        <div class="row g-4 mb-4">
            @foreach ([
                ['title' => 'إجمالي المعدات', 'value' => $totalEquipments, 'gradient' => '--primary-gradient', 'icon' => 'bi bi-tools'],
                ['title' => 'المعدات الجاهزة', 'value' => $availableEquipments, 'gradient' => '--success-gradient', 'icon' => 'bi bi-check-circle'],
                ['title' => 'المعدات المعطلة', 'value' => $maintenanceEquipments, 'gradient' => '--danger-gradient', 'icon' => 'bi bi-exclamation-circle']
            ] as $stat)
                <div class="col-12 col-md-4">
                    <div class="card stats-card border-0 shadow-sm" style="background-image: var({{ $stat['gradient'] }});">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="{{ $stat['icon'] }} fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle mb-1 text-white-50">{{ $stat['title'] }}</h6>
                                    <h2 class="card-title mb-0">{{ $stat['value'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="equipmentsTable" class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>اسم المعدة</th>
                                <th>الوصف</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($equipments as $equipment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $equipment->name }}</td>
                                    <td>{{ $equipment->description ?? 'لا يوجد' }}</td>
                                    <td>
                                        <span class="badge {{ $equipment->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $equipment->status == 'available' ? 'جاهزة' : 'معطلة' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons d-flex gap-1">
                                            @can('manage-equipment')
                                            <a href="{{ route('admin.equipments.show', $equipment->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                                <a href="{{ route('admin.equipments.edit', $equipment->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('admin.equipments.destroy', $equipment->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $equipments->links() }}
                    </div>
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
            $('#equipmentsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                },
                pageLength: 10,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [-1] }
                ]
            });
        });
    </script>
@endpush
