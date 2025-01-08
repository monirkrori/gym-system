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
                            <p class="text-4xl font-bold">{{ $membershipMetrics['activeMembers'] }}</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="{{ $membershipMetrics['newMembersPercentage'] > 0 ? 'text-green-200' : 'text-red-200' }}">
                                    <i class="bi {{ $membershipMetrics['newMembersPercentage'] > 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} ml-1"></i>
                                    {{ $membershipMetrics['newMembersPercentage']}}%
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
                            <p class="text-4xl font-bold">{{ number_format($revenueMetrics['monthlyRevenue']) }} ل.س</p>
                            <div class="flex items-center mt-2 text-sm">
                                <span class="{{ $revenueMetrics['revenueGrowth'] > 0 ? 'text-green-200' : 'text-red-200' }}">
                                    <i class="bi {{ $revenueMetrics['revenueGrowth'] > 0 ? 'bi-arrow-up' : 'bi-arrow-down' }} ml-1"></i>
                                    {{ $revenueMetrics['revenueGrowth'] }}%
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
                            <p class="text-4xl font-bold">{{ $sessionMetrics['todaySessions'] }}</p>
                            <p class="mt-2 text-sm opacity-75">{{$sessionMetrics['activeTrainers']}} مدرب متاح</p>
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
        <div class="col-span-2 bg-white shadow-xl rounded-2xl p-6 transform transition duration-300 hover:shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-semibold text-2xl text-gray-800 flex items-center">
                    <i class="bi bi-activity text-blue-500 text-3xl mr-3"></i>
                    آخر النشاطات
                </h4>
               
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-600">
                        <tr>
                            <th class="px-6 py-4 text-white text-sm font-semibold uppercase">النشاط</th>
                            <th class="px-6 py-4 text-white text-sm font-semibold uppercase">العضو</th>
                            <th class="px-6 py-4 text-white text-sm font-semibold uppercase">النوع</th>
                            <th class="px-6 py-4 text-white text-sm font-semibold uppercase">التاريخ</th>
                            <th class="px-6 py-4 text-white text-sm font-semibold uppercase">الحالة</th>
                        </tr>
                    </thead>
                    <tbody id="activities-table-body" class="divide-y divide-gray-200">
                        @forelse($notifications as $notification)
                            <tr class="hover:bg-gray-50 transition duration-200">
                                <td class="px-6 py-4 text-gray-700">{{ $notification->description }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $notification->user->profile->profile_photo ?? asset('images/default-avatar.png') }}"
                                             class="rounded-full w-10 h-10 mr-3 object-cover border-2 border-blue-200"
                                             alt="{{ $notification->user->name }}">
                                        <span class="text-gray-800 font-medium">{{ $notification->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-{{ $notification->type_color }}">
                                        {{ $notification->type_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $notification->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white bg-{{ $notification->status_color }}">
                                        {{ $notification->status_name }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    <i class="bi bi-inbox text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-lg">لا توجد نشاطات حديثة</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endcan

    <!-- الإجراءات السريعة -->
    @canany(['create-member', 'create-session', 'create-trainer', 'create-plans'])
        <div class="bg-white shadow-xl rounded-2xl p-6 transform transition duration-300 hover:shadow-2xl">
            <h4 class="font-semibold text-2xl text-gray-800 mb-6 flex items-center">
                <i class="bi bi-lightning text-yellow-500 text-3xl mr-3"></i>
                إجراءات سريعة
            </h4>
            <div class="space-y-4">
                @can('create-member')
                    <a href="{{ route('admin.memberships.create') }}" class="block w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="bi bi-person-plus text-lg mr-2"></i>
                        إضافة عضو جديد
                    </a>
                @endcan
                @can('create-session')
                    <a href="{{ route('admin.sessions.create') }}" class="block w-full bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="bi bi-calendar-plus text-lg mr-2"></i>
                        إنشاء جلسة تدريبية
                    </a>
                @endcan
                @can('create-trainer')
                    <a href="{{ route('admin.trainers.create') }}" class="block w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="bi bi-person-workspace text-lg mr-2"></i>
                        إضافة مدرب جديد
                    </a>
                @endcan
                @can('create-plans')
                    <a href="{{ route('admin.membership-plans.create') }}" class="block w-full bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg py-3 px-4 text-center transition duration-300 ease-in-out transform hover:scale-105 shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="bi bi-person-check text-lg mr-2"></i>
                        إضافة خطة عضوية
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

 <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
 <script>
     const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
         cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
     });

     const channel = pusher.subscribe('notifications');

          channel.bind('user.registered', function(data) {
              addActivityToTable(data);
          });

     channel.bind('membership.registered', function(data) {
         addActivityToTable(data);
     });

     channel.bind('membershipPackage.registered', function(data) {
              addActivityToTable(data);
          });

     channel.bind('training.session.created', function(data) {
         addActivityToTable(data);
     });

     channel.bind('session.created', function(data) {
         addActivityToTable(data);
     });

     function addActivityToTable(data) {
         const tableBody = document.getElementById('activities-table-body');

         const newRow = `
             <tr class="border-b hover:bg-gray-50 transition">
                 <td class="px-4 py-3">${data.description}</td>
                 <td class="px-4 py-3 flex items-center">
                     <img src="${data.user.profile_photo || '{{ asset('images/default-avatar.png') }}'}"
                          class="rounded-full w-10 h-10 mr-3 object-cover"
                          alt="${data.user.name}">
                     <span>${data.user.name}</span>
                 </td>
                 <td class="px-4 py-3">
                     <span class="px-2 py-1 rounded-full text-xs text-white bg-${data.type_color}">
                         ${data.type_name}
                     </span>
                 </td>
                 <td class="px-4 py-3 text-gray-600">${data.created_at}</td>
                 <td class="px-4 py-3">
                     <span class="px-2 py-1 rounded-full text-xs text-white bg-${data.status_color}">
                         ${data.status_name}
                     </span>
                 </td>
             </tr>
         `;

         tableBody.insertAdjacentHTML('afterbegin', newRow);

         // إزالة الرسالة "لا توجد نشاطات حديثة" إذا كانت موجودة
         const emptyRow = tableBody.querySelector('tr td[colspan="5"]');
         if (emptyRow) {
             emptyRow.remove();
         }
     }
 </script>
@endpush
