@extends('layouts.dashboard')

@section('title', 'إدارة الجلسات التجريبية')

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
        @can('view-session')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="bi bi-calendar-check me-2"></i> إدارة الجلسات التجريبية
                </h1>

                <div class="d-flex align-items-center gap-2">
                    @can('create-session')
                        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> إضافة جلسة
                        </a>
                    @endcan
                </div>
            </div>


            <!-- Cards Section -->
            <div class="row g-4 mb-4">
                <div class="col-12 col-md-6">
                    <div class="card stats-card border-0 shadow-sm" style="background-image: var(--primary-gradient);">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-calendar-check fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle mb-1 text-white-50">إجمالي الجلسات</h6>
                                    <h2 class="card-title mb-0">{{ $totalSessions }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card stats-card border-0 shadow-sm" style="background-image: var(--success-gradient);">
                        <div class="card-body text-white">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="card-subtitle mb-1 text-white-50">الجلسات القادمة</h6>
                                    <h2 class="card-title mb-0">{{ $upcomingSessions }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

               <div class="card border-0 shadow-sm mb-4">
                                      <div class="card-body">
                                          <form action="{{ route('admin.sessions.index') }}" method="GET" class="row g-3">
                                              <div class="col-md-4">
                                                  <input type="text" name="name" class="form-control" placeholder="ابحث عن اسم الجلسة التدريبية..." value="{{ request('search') }}">
                                              </div>
                                         <div class="col-md-4">
                                             <select name="status" class="form-select">
                                                 <option value="">جميع الحالات</option>
                                                 <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                                 <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
                                                 <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                                             </select>
                                         </div>
                                              <div class="col-md-4 text-md-end">
                                                  <button type="submit" class="btn btn-primary">
                                                      <i class="bi bi-search"></i> فلترة
                                                  </button>
                                                  <a href="{{ route('admin.equipments.index') }}" class="btn btn-secondary">
                                                      <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                                                  </a>
                                              </div>
                                          </form>
                                      </div>
                                  </div>


            <!-- Main Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="sessionsTable" class="table table-hover">
                            <thead class="table-light">
                            <tr>

                                <th>اسم الجلسة</th>
                                <th>المدرب</th>
                                <th>العدد المتاح للحضور</th>
                                <th>الحضور</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($sessions as $session)
                                <tr>

                                    <td>{{ $session->name }}</td>
                                    <td>{{ $session->trainer->user->name }}</td>
                                    <td>{{ $session->max_capacity }}</td>
                                    <td>{{ $session->current_capacity }}</td>
<td>
    <span class="badge badge-status
        @if ($session->status === 'active')
            bg-success
        @elseif ($session->status === 'expired')
            bg-danger
        @elseif ($session->status === 'scheduled')
            bg-warning
        @endif
    ">
        @if ($session->status === 'active')
            نشطة
        @elseif ($session->status === 'expired')
            منتهية
        @elseif ($session->status === 'scheduled')
            مجدولة
        @endif
    </span>
</td>
                                    <td>
                                        <div class="action-buttons d-flex gap-1">
                                            <a href="{{ route('admin.sessions.show', $session->id) }}" class="btn btn-sm btn-info" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @can('edit-session')
                                                <a href="{{ route('admin.sessions.edit', $session->id) }}" class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan

                                            @can('delete-session')
                                                <form action="{{ route('admin.sessions.destroy', $session->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذه الجلسة؟')">
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
                            {{ $sessions->links() }}
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
            $('#sessionsTable').DataTable({
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
