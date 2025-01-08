<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Models\Notification;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $membershipMetrics = $this->dashboardService->calculateMembershipMetrics();
        $revenueMetrics = $this->dashboardService->calculateMonthlyRevenue();
        $sessionMetrics = $this->dashboardService->calculateSessionAndTrainerMetrics();
        $attendanceRate = $this->dashboardService->calculateAttendanceRate();
        $membershipStats = $this->dashboardService->getMembershipStatsForLastSixMonths();
        $packageDistribution = $this->dashboardService->getPackageDistribution();
        $latestActivities = $this->dashboardService->getLatestActivities();
        $todaySchedule = $this->dashboardService->getTodaySchedule();
        $notifications = Notification::with('user')->latest()->get();

        return view('dashboard.index', compact(
            'membershipMetrics',
            'revenueMetrics',
            'sessionMetrics',
            'attendanceRate',
            'membershipStats',
            'packageDistribution',
            'latestActivities',
            'todaySchedule',
            'notifications'
        ));
    }

    public function reports()
    {
        return view('dashboard.reports', [
            'monthlyRevenue' => 15000,
            'activeMembers' => 120,
            'expiredMemberships' => 30,
            'trainersCount' => 10,
            'revenueMonths' => ['يناير', 'فبراير', 'مارس', 'أبريل'],
            'monthlyRevenueData' => [5000, 7000, 8000, 6000],
            'members' => $this->dashboardService->getActiveMembersCount(),
        ]);
    }
}
