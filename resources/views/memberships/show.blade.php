@extends('layouts.dashboard')

@section('title', 'تفاصيل العضوية')

@push('styles')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>

        :root {
            --primary-gradient: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            --secondary-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --danger-gradient: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
        }

        body {
            background-color: #f4f7fa;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
            transition: background-color 0.3s ease;
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

        .stats-card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(50,50,93,.1), 0 5px 15px rgba(0,0,0,.07);
        }

        @keyframes gradientShift {
            0% { background-position: 0 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0 50%; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        @can('view-member')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-person-fill me-2"></i> تفاصيل العضوية
                </h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.memberships.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> العودة للقائمة
                    </a>
                    @can('edit-membership')
                        <a href="{{ route('admin.memberships.edit', $membership->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i> تعديل
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Membership Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">معلومات العضوية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- User Profile -->
                        <div class="col-md-3 text-center mb-4">
                            <img src="{{  asset('storage/' . $membership->user->profile_photo) }}" alt="User Image" class="profile-img mb-3">

                            <h4 class="fw-bold">{{ $membership->user?->name }}</h4>
                            <p class="text-muted">{{ $membership->user?->email }}</p>
                        </div>

                        <!-- Membership Information -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>الخطة:</strong> {{ $membership->plan?->name }}</p>
                                    <p><strong>الباقة:</strong> {{ $membership->package?->name }}</p>
                                    <p><strong>تاريخ بدء العضوية:</strong> {{ \Carbon\Carbon::parse($membership->start_date)->format('Y-m-d') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>تاريخ انتهاء العضوية:</strong> {{ \Carbon\Carbon::parse($membership->end_date)->format('Y-m-d') }}</p>
                                    <p><strong>الحالة:</strong>
                                        <span class="badge badge-status {{ $membership->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $membership->status === 'active' ? 'نشط' : 'منتهي' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-shield-lock me-2"></i> ليس لديك الصلاحية للوصول إلى هذه الصفحة
            </div>
        @endcan
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
