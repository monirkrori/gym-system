@extends('layouts.dashboard')

@section('title', 'إدارة العضويات')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Enhanced Visual Styling */
        :root {
            --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --secondary-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
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
            position: relative;
            background-size: 200% 200%;
            background-position: 0 0;
            animation: gradientShift 10s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }

        .stats-card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(50,50,93,.1), 0 5px 15px rgba(0,0,0,.07);
        }

        .stats-card .card-body {
            position: relative;
            z-index: 2;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            background-color: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
            transition: background-color 0.3s ease;
        }

        .action-buttons .btn {
            margin: 0 2px;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .dropdown-item {
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f4f4f4;
            transform: translateX(5px);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header Section with Permissions Check -->
        @can('view-member')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <!-- Title -->
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-people-fill me-2"></i> إدارة العضويات
                </h1>

                <!-- Buttons Section -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Add Membership Button -->
                    @can('create-membership')
                        <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> إضافة عضوية
                        </a>
                    @endcan

                    <!-- Export Buttons -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-1"></i> تصدير
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.trainers.export', 'pdf') }}">
                                    <i class="bi bi-file-earmark-pdf me-2 text-danger"></i> PDF
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.trainers.export', 'excel') }}">
                                    <i class="bi bi-file-earmark-spreadsheet me-2 text-success"></i> Excel
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endcan
    </div>


            <!-- Stats Cards with Gradient Backgrounds -->
            <div class="row g-4 mb-4">
                @can('view-membership-statistics')
                    <div class="col-12 col-md-4">
                        <div class="card stats-card border-0 shadow-sm" style="background-image: var(--primary-gradient);">
                            <div class="card-body text-white">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1 text-white-50">إجمالي العضويات</h6>
                                        <h2 class="card-title mb-0">{{ $totalMembers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card stats-card border-0 shadow-sm" style="background-image: var(--success-gradient);">
                            <div class="card-body text-white">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-check-circle fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1 text-white-50">العضويات النشطة</h6>
                                        <h2 class="card-title mb-0">{{ $activeMembers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card stats-card border-0 shadow-sm" style="background-image: var(--danger-gradient);">
                            <div class="card-body text-white">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3">
                                        <i class="bi bi-x-circle fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-subtitle mb-1 text-white-50">العضويات المنتهية</h6>
                                        <h2 class="card-title mb-0">{{ $expiredMembers }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>

            <!-- Main Content Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="membershipsTable" class="table table-hover">
                            <thead class="table-light">
                            <tr>
                                <th>اسم المستخدم</th>
                                <th>الخطة</th>
                                <th>الباقة</th>
                                <th>تاريخ بدء العضوية</th>
                                <th>تاريخ انتهاء العضوية</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($memberships as $membership)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $membership->user->profile_photo) }}"
                                             class="rounded-circle me-3 border border-2 border-primary"
                                             width="80"
                                             height="80"
                                             alt="صورة العضو"
                                             style="object-fit: cover;">
                                            <div>
                                                <div class="fw-bold">{{ $membership->user?->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $membership->plan?->name}}</td>
                                    <td>{{ $membership->package?->name}}</td>
                                    <td>{{\Carbon\Carbon::parse($membership->start_date)->format('Y-m-d')}}</td>
                                    <td>{{\Carbon\Carbon::parse($membership->end_date)->format('Y-m-d')}}</td>
                                    <td>
                                        <span class="badge badge-status {{ $membership->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $membership->status === 'active' ? 'نشط' : 'منتهي' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons d-flex gap-1">
                                            @can('view-membership-details')
                                                <a href="{{ route('admin.memberships.show', $membership->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   title="عرض">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan

                                            @can('edit-membership')
                                                <a href="{{ route('admin.memberships.edit', $membership->id) }}"
                                                   class="btn btn-sm btn-warning"
                                                   title="تعديل">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete-membership')
                                                <form action="{{ route('admin.memberships.destroy', $membership->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="حذف"
                                                            onclick="return confirm('هل أنت متأكد من حذف هذه العضوية؟')">
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
            @can('view-member')
                        <div class="mt-4">
                            {{ $memberships->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-shield-lock me-1"></i> ليس لديك الصلاحية للوصول إلى هذه الصفحة
            </div>

        @endcan

    </div>
    <div class="d-flex justify-content-start">
        <a href="{{ route('admin.memberships.deleted') }}" class="btn btn-outline-secondary">
            <i class="bi bi-trash me-2"></i>العضويات المحذوفة
        </a>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#membershipsTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                },
                pageLength: 10,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [-1] } // Disable sorting for action column
                ]
            });
        });
    </script>
@endpush
