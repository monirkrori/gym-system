{{-- resources/views/dashboard/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
    <div class="container-fluid">
        <!-- Stats Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stats-card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">الأعضاء النشطين</h6>
                                <h2 class="mt-2 mb-0">{{ $activeMembers }}</h2>
                            </div>
                            <i class="bi bi-people fs-1"></i>
                        </div>
                        <div class="mt-3">
                            <small>زيادة {{ $newMembersPercentage }}% عن الشهر الماضي</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">الإيرادات الشهرية</h6>
                                <h2 class="mt-2 mb-0">{{ number_format($monthlyRevenue) }} ر.س</h2>
                            </div>
                            <i class="bi bi-currency-dollar fs-1"></i>
                        </div>
                        <div class="mt-3">
                            <small>{{ $revenueGrowth > 0 ? '+' : '' }}{{ $revenueGrowth }}% عن الشهر السابق</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">الجلسات اليوم</h6>
                                <h2 class="mt-2 mb-0">{{ $todaySessions }}</h2>
                            </div>
                            <i class="bi bi-calendar-event fs-1"></i>
                        </div>
                        <div class="mt-3">
                            <small>{{ $activeTrainers }} مدرب متاح</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title mb-0">معدل الحضور</h6>
                                <h2 class="mt-2 mb-0">{{ $attendanceRate }}%</h2>
                            </div>
                            <i class="bi bi-graph-up fs-1"></i>
                        </div>
                        <div class="mt-3">
                            <small>في آخر 7 أيام</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">إحصائيات العضوية</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="membershipStats" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">توزيع الباقات</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="packageDistribution
                                         " height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Activities & Quick Actions -->
        <div class="row">
            <!-- Latest Activities -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">آخر النشاطات</h5>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-primary">عرض الكل</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>النشاط</th>
                                    <th>العضو</th>
                                    <th>النوع</th>
                                    <th>التاريخ</th>
                                    <th>الحالة</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($latestActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->description }}</td>
                                        <td>
                                            <img src="{{ $activity->user->profile->profile_photo ?? asset('images/default-avatar.png') }}"
                                                 class="rounded-circle me-2"
                                                 width="32"
                                                 height="32"
                                                 alt="{{ $activity->user->name }}">
                                            {{ $activity->user->name }}
                                        </td>
                                        <td>
                                            @switch($activity->type)
                                                @case('membership')
                                                <span class="badge bg-primary">عضوية</span>
                                                @break
                                                @case('session')
                                                <span class="badge bg-success">جلسة تدريبية</span>
                                                @break
                                                @case('attendance')
                                                <span class="badge bg-info">حضور</span>
                                                @break
                                                @default
                                                <span class="badge bg-secondary">عام</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                                        <td>
                                            @switch($activity->status)
                                                @case('completed')
                                                <span class="badge bg-success">مكتمل</span>
                                                @break
                                                @case('pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                                @break
                                                @case('cancelled')
                                                <span class="badge bg-danger">ملغي</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">لا توجد نشاطات حديثة</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">إجراءات سريعة</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            @can('create-member')
                                <a href="{{ route('members.create') }}" class="btn btn-primary">
                                    <i class="bi bi-person-plus me-2"></i>
                                    إضافة عضو جديد
                                </a>
                            @endcan

                            @can('create-session')
                                <a href="{{ route('sessions.create') }}" class="btn btn-success">
                                    <i class="bi bi-calendar-plus me-2"></i>
                                    إنشاء جلسة تدريبية
                                </a>
                            @endcan

                            @can('create-trainer')
                                <a href="{{ route('trainers.create') }}" class="btn btn-info text-white">
                                    <i class="bi bi-person-plus-fill me-2"></i>
                                    إضافة مدرب جديد
                                </a>
                            @endcan

                            @can('view-reports')
                                <a href="{{ route('reports.generate') }}" class="btn btn-warning text-white">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    إنشاء تقرير
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">جدول اليوم</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($todaySchedule as $session)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $session->name }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $session->status === 'scheduled' ? 'primary' : ($session->status === 'ongoing' ? 'success' : 'secondary') }}">
                                    {{ __('sessions.status.' . $session->status) }}
                                </span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $session->current_capacity }}/{{ $session->max_capacity }} مشترك
                                        </small>
                                        <small class="text-muted ms-3">
                                            <i class="bi bi-person me-1"></i>
                                            {{ $session->trainer->user->name }}
                                        </small>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center">
                                    لا توجد جلسات مجدولة لليوم
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // Membership Stats Chart
        const membershipCtx = document.getElementById('membershipStats').getContext('2d');
        new Chart(membershipCtx, {
            type: 'line',
            data: {
                labels: @json($membershipStats->pluck('month')),
                datasets: [{
                    label: 'عضويات جديدة',
                    data: @json($membershipStats->pluck('new_members')),
                    borderColor: '#3498db',
                    tension: 0.4,
                    fill: false
                }, {
                    label: 'عضويات منتهية',
                    data: @json($membershipStats->pluck('expired_members')),
                    borderColor: '#e74c3c',
                    tension: 0.4,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Package Distribution Chart
        const packageCtx = document.getElementById('packageDistribution').getContext('2d');
        new Chart(packageCtx, {
            type: 'doughnut',
            data: {
                labels: @json($packageDistribution->pluck('name')),
                datasets: [{
                    data: @json($packageDistribution->pluck('count')),
                    backgroundColor: [
                        '#3498db',
                        '#2ecc71',
                        '#f1c40f',
                        '#e74c3c',
                        '#9b59b6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endpush
