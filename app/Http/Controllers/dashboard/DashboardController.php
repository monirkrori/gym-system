<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserMembership;
use App\Repositories\DashboardRepository;
use App\Services\DashboardService;
use function view;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $userMembershipRepository;

    public function __construct(
        DashboardService $dashboardService,
        DashboardRepository $userMembershipRepository
    ) {
        $this->dashboardService = $dashboardService;
        $this->userMembershipRepository = $userMembershipRepository;
    }

    public function index()
    {
        $membershipMetrics = $this->dashboardService->calculateMembershipMetrics();
        $revenueMetrics = $this->dashboardService->calculateMonthlyRevenue();
        $sessionMetrics = $this->dashboardService->calculateSessionAndTrainerMetrics();
        $attendanceRate = $this->dashboardService->calculateAttendanceRate();
        $membershipStats = $this->userMembershipRepository->getMembershipStatsForLastSixMonths();
        $packageDistribution = $this->dashboardService->getPackageDistribution();
        $latestActivities = $this->dashboardService->getLatestActivities();
        $todaySchedule = $this->dashboardService->getTodaySchedule();

        return view('dashboard.index', compact(
            'membershipMetrics',
            'revenueMetrics',
            'sessionMetrics',
            'attendanceRate',
            'membershipStats',
            'packageDistribution',
            'latestActivities',
            'todaySchedule'
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
            'members' => UserMembership::paginate(10),
        ]);
    }
}
