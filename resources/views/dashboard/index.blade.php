@extends('layouts.dashboard')
@section('title', 'لوحة التحكم الرئيسية')
@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 320px;
            width: 100%;
        }
    </style>
@endpush
@section('content')

    <div class="container-fluid px-4 py-6 bg-gray-50 min-h-screen">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">مرحباً </h1>
            <p class="text-gray-600">نظرة عامة على أداء النادي الرياضي</p>
        </div>

        <!-- بطاقات الإحصائيات -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @can('view-members')
                <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-lg rounded-2xl p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 opacity-80">الأعضاء النشطين</h3>
                            <p class="text-4xl font-bold">{{ $activeMembers }}</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="{{ $newMembersPercentage > 0 ? 'text-green-200' : 'text-red-200' }}">
                                    <i class="bi {{ $newMembersPercentage > 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} ml-1"></i>
                                    {{ $newMembersPercentage }}%
                                </span>
                                <span class="mr-2 opacity-75">عن الشهر الماضي</span>
                            </div>
                        </div>
                        <i class="bi bi-people text-6xl opacity-50"></i>
                    </div>
                </div>
            @endcan

            @can('view-revenue')
                <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-gradient-to-br from-green-500 to-green-700 text-white shadow-lg rounded-2xl p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 opacity-80">الإيرادات الشهرية</h3>
                            <p class="text-4xl font-bold">{{ number_format($monthlyRevenue) }} ر.س</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="{{ $revenueGrowth > 0 ? 'text-green-200' : 'text-red-200' }}">
                                    <i class="bi {{ $revenueGrowth > 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} ml-1"></i>
                                    {{ $revenueGrowth }}%
                                </span>
                                <span class="mr-2 opacity-75">عن الشهر الماضي</span>
                            </div>
                        </div>
                        <i class="bi bi-currency-dollar text-6xl opacity-50"></i>
                    </div>
                </div>
            @endcan

            @can('view-sessions')
                <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-700 text-white shadow-lg rounded-2xl p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 opacity-80">الجلسات اليوم</h3>
                            <p class="text-4xl font-bold">{{ $todaySessions }}</p>
                            <p class="mt-2 text-sm opacity-75">{{ $activeTrainers }} مدرب متاح</p>
                        </div>
                        <i class="bi bi-calendar-event text-6xl opacity-50"></i>
                    </div>
                </div>
            @endcan

            @can('view-attendance')
                <div class="transform transition duration-300 hover:scale-105 hover:shadow-xl">
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 text-white shadow-lg rounded-2xl p-6 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 opacity-80">معدل الحضور</h3>
                            <p class="text-4xl font-bold">{{ $attendanceRate }}%</p>
                            <p class="mt-2 text-sm opacity-75">في آخر 7 أيام</p>
                        </div>
                        <i class="bi bi-graph-up text-6xl opacity-50"></i>
                    </div>
                </div>
            @endcan
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @can('view-membership-stats')
                <div class="col-span-2 bg-white shadow-lg rounded-2xl p-6 transition duration-300 hover:shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-xl text-gray-800">إحصائيات العضوية</h4>
                        <div class="flex items-center space-x-2 rtl:space-x-reverse">
                            <span class="text-sm text-gray-500">{{ now()->format('Y') }}</span>
                            <button id="toggleMembershipChartType" class="text-blue-500 hover:text-blue-700 text-sm">
                                <i class="bi bi-arrow-repeat"></i> تبديل النوع
                            </button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="membershipStats"></canvas>
                    </div>
                </div>
            @endcan

                @can('view-package-distribution')
                    <div class="bg-white shadow-lg rounded-2xl p-6 transition duration-300 hover:shadow-xl">
                        <h4 class="font-semibold text-xl text-gray-800 mb-4">توزيع الباقات</h4>
                        <div class="chart-container">
                            <canvas id="packageDistribution"></canvas>
                        </div>
                    </div>
                @endcan
        </div>
        <!-- النشاطات والإجراءات السريعة -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            @can('view-activities')
                <div class="col-span-2 bg-white shadow-lg rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-xl text-gray-800">آخر النشاطات</h4>
                        @can('list-activities')
                            <a href="{{ route('admin.activities.index') }}" class="text-blue-600 hover:text-blue-800 transition">عرض الكل</a>
                        @endcan
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-gray-600">النشاط</th>
                                <th class="px-4 py-3 text-gray-600">العضو</th>
                                <th class="px-4 py-3 text-gray-600">النوع</th>
                                <th class="px-4 py-3 text-gray-600">التاريخ</th>
                                <th class="px-4 py-3 text-gray-600">الحالة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($latestActivities as $activity)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">{{ $activity->description }}</td>
                                    <td class="px-4 py-3 flex items-center">
                                        <img src="{{ $activity->user->profile->profile_photo ?? asset('images/default-avatar.png') }}"
                                             class="rounded-full w-10 h-10 mr-3 object-cover"
                                             alt="{{ $activity->user->name }}">
                                        <span>{{ $activity->user->name }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs text-white bg-{{ $activity->type_color }}">
                                                {{ $activity->type_name }}
                                            </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $activity->created_at->diffForHumans() }}</td>
                                    <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs text-white bg-{{ $activity->status_color }}">
                                                {{ $activity->status_name }}
                                            </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-gray-500">لا توجد نشاطات حديثة</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endcan

        <!-- الإجراءات السريعة -->
            @canany(['create-member', 'create-session', 'create-trainer', 'create-plans'])
                <div class="bg-white shadow-lg rounded-2xl p-6">
                    <h4 class="font-semibold text-xl text-gray-800 mb-6">إجراءات سريعة</h4>
                    <div class="space-y-4">
                        @can('create-member')
                            <a href="{{route('admin.memberships.create')}}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg">
                                <i class="bi bi-person-plus ml-2"></i>إضافة عضو جديد
                            </a>
                        @endcan
                        @can('create-session')
                            <a href="{{route('admin.sessions.create')}}" class="block w-full bg-green-500 hover:bg-green-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg">
                                <i class="bi bi-calendar-plus ml-2"></i>إنشاء جلسة تدريبية
                            </a>
                        @endcan
                        @can('create-trainer')
                            <a href="{{route('admin.trainers.create')}}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg">
                                <i class="bi bi-person-workspace ml-2"></i>إضافة مدرب جديد
                            </a>
                        @endcan
                       @can('create-plans')
                           <a href="{{route('admin.membership-plans.create')}}" class="block w-full bg-gradient-to-br from-yellow-500 to-yellow-700 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg">
                               <i class="bi bi-person-check ml-2"></i>إضافة خطة عضوية
                           </a>
                       @endcan

                    </div>
                </div>
            @endcanany
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @can('view-membership-stats')
            // Membership Stats Chart
            const membershipCtx = document.getElementById('membershipStats').getContext('2d');
            let membershipChartType = 'line';
            let membershipChart;

            function createMembershipChart(type) {
                if (membershipChart) {
                    membershipChart.destroy();
                }

                membershipChart = new Chart(membershipCtx, {
                    type: type,
                    data: {
                        labels: @json($membershipStats->pluck('month')),
                        datasets: [
                            {
                                label: 'عضويات جديدة',
                                data: @json($membershipStats->pluck('new_members')),
                                borderColor: '#3498db',
                                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                                tension: 0.4,
                                fill: type === 'area'
                            },
                            {
                                label: 'عضويات منتهية',
                                data: @json($membershipStats->pluck('expired_members')),
                                borderColor: '#e74c3c',
                                backgroundColor: 'rgba(231, 76, 60, 0.2)',
                                tension: 0.4,
                                fill: type === 'area'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Initial chart
            createMembershipChart(membershipChartType);

            // Toggle chart type
            document.getElementById('toggleMembershipChartType').addEventListener('click', function() {
                membershipChartType = membershipChartType === 'line' ? 'bar' : 'line';
                createMembershipChart(membershipChartType);
            });
            @endcan

            @can('view-package-distribution')
            // Package Distribution Chart
            const packageCtx = document.getElementById('packageDistribution').getContext('2d');
            const packageChart = new Chart(packageCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($packageDistribution->pluck('name')),
                    datasets: [{
                        data: @json($packageDistribution->pluck('count')),
                        backgroundColor: @json($packageDistribution->pluck('color')),
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
            @endcan
        });
    </script>
@endpush

