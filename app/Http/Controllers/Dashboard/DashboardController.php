<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\Dashboard\DashboardService;

class DashboardController extends Controller
{
    // Declare a protected property for the dashboard service to handle dashboard logic
    protected $dashboardService;

    /**
     * Constructor method to initialize the DashboardService.
     *
     * @param DashboardService $dashboardService The dashboard service instance.
     */
    public function __construct(DashboardService $dashboardService)
    {
        // Assign the dashboard service to the controller property
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard index page with various metrics and data.
     *
     * This method retrieves and calculates various metrics related to memberships,
     * revenue, sessions, attendance, etc., and then passes them to the dashboard view.
     *
     * @return \Illuminate\View\View The view containing the dashboard data.
     */
    public function index()
    {
        // Calculate the membership metrics using the dashboard service
        $membershipMetrics = $this->dashboardService->calculateMembershipMetrics();

        // Calculate the monthly revenue metrics using the dashboard service
        $revenueMetrics = $this->dashboardService->calculateMonthlyRevenue();

        // Calculate session and trainer metrics using the dashboard service
        $sessionMetrics = $this->dashboardService->calculateSessionAndTrainerMetrics();

        // Calculate the attendance rate using the dashboard service
        $attendanceRate = $this->dashboardService->calculateAttendanceRate();

        // Get the membership statistics for the last six months using the dashboard service
        $membershipStats = $this->dashboardService->getMembershipStatsForLastSixMonths();

        // Get the distribution of membership packages using the dashboard service
        $packageDistribution = $this->dashboardService->getPackageDistribution();

        // Get the latest activities that have occurred using the dashboard service
        $latestActivities = $this->dashboardService->getLatestActivities();

        // Get today's schedule using the dashboard service
        $todaySchedule = $this->dashboardService->getTodaySchedule();

        // Retrieve the latest notifications along with the user information who triggered them
        $notifications = Notification::with('user')->latest()->get();

        // Return the dashboard view and pass the calculated data to the view
        return view('dashboard.index', compact(
            'membershipMetrics',  // Pass the membership metrics
            'revenueMetrics',     // Pass the revenue metrics
            'sessionMetrics',     // Pass the session metrics
            'attendanceRate',     // Pass the attendance rate
            'membershipStats',    // Pass the membership stats for the last six months
            'packageDistribution',// Pass the package distribution data
            'latestActivities',   // Pass the latest activities
            'todaySchedule',      // Pass today's schedule
            'notifications'       // Pass the latest notifications
        ));
    }
}
