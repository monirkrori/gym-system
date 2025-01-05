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
    protected $dashboardRepository;

    public function __construct(
        DashboardService    $dashboardService,
        DashboardRepository $dashboardRepository
    )
    {
        $this->dashboardService = $dashboardService;
        $this->dashboardRepository = $dashboardRepository;
    }

    public function index()
    {
        $membershipMetrics = $this->dashboardService->calculateMembershipMetrics();
        $revenueMetrics = $this->dashboardService->calculateMonthlyRevenue();
        $sessionMetrics = $this->dashboardService->calculateSessionAndTrainerMetrics();
        $attendanceRate = $this->dashboardService->calculateAttendanceRate();
        $membershipStats = $this->dashboardRepository->getMembershipStatsForLastSixMonths();
        $packageDistribution = $this->dashboardService->getPackageDistribution();
        $latestActivities = $this->dashboardService->getLatestActivities();
        $todaySchedule = $this->dashboardService->getTodaySchedule();

        $activeMembers = UserMembership::where('status', 'active')->count();
        $revenueData = $this->dashboardService->calculateMonthlyRevenue();
        $monthlyRevenue = $revenueData['monthlyRevenue'];
        $todaySessions = $this->dashboardService->getTodaySchedule();

        return view('dashboard.index', compact(
            'membershipMetrics',
            'revenueMetrics',
            'sessionMetrics',
            'attendanceRate',
            'membershipStats',
            'packageDistribution',
            'latestActivities',
            'todaySchedule',
            'activeMembers',
            'monthlyRevenue',
            'todaySessions',
           
        ));
    }

    public function reports()
    {
        return view('dashboard.reports', [
            'monthlyRevenue' => 15000, // Example: Monthly Revenue
            'activeMembers' => 120, // Example: Active Members
            'expiredMemberships' => 30, // Example: Expired Memberships
            'trainersCount' => 10, // Example: Trainers Count
            'revenueMonths' => ['يناير', 'فبراير', 'مارس', 'أبريل'], // Example: Months
            'monthlyRevenueData' => [5000, 7000, 8000, 6000], // Example: Revenue Data
            'members' => UserMembership::paginate(10), // Example: Members Pagination
        ]);
    }
}
