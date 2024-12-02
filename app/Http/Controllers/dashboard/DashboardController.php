<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AttendanceLog;
use App\Models\MembershipPackage;
use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Models\UserMembership;
use Carbon\Carbon;
use function collect;
use function view;

class DashboardController extends Controller
{
    public function index()
    {
        // Get active members count and growth percentage
        $activeMembers = UserMembership::where('status', 'active')->count();

        $lastMonthMembers = UserMembership::where('status', 'active')
            ->where('user_memberships.created_at', '<=', Carbon::now()->subMonth()) // تحديد الجدول
            ->count();

        $newMembersPercentage = $lastMonthMembers > 0
            ? round((($activeMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1)
            : 0;

        // Calculate monthly revenue
        $monthlyRevenue = UserMembership::join('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->month) // تحديد الجدول
            ->sum('membership_packages.price');

        $lastMonthRevenue = UserMembership::join('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->subMonth()->month) // تحديد الجدول
            ->sum('membership_packages.price');

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        // Get today's sessions and active trainers
        $todaySessions = TrainingSession::whereDate('start_time', Carbon::today())->count();
        $activeTrainers = Trainer::where('status', 'available')->count();

        // Calculate attendance rate for last 7 days
        $totalAttendance = AttendanceLog::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $totalExpectedAttendance = UserMembership::where('status', 'active')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now()->subDays(7))
                ->count() * 7;

        $attendanceRate = $totalExpectedAttendance > 0
            ? round(($totalAttendance / $totalExpectedAttendance) * 100, 1)
            : 0;

        // Get membership statistics for the last 6 months
        $membershipStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $membershipStats->push([
                'month' => $date->format('M'),
                'new_members' => UserMembership::whereMonth('user_memberships.created_at', $date->month)
                    ->whereYear('user_memberships.created_at', $date->year)
                    ->count(),
                'expired_members' => UserMembership::where('status', 'expired')
                    ->whereMonth('user_memberships.end_date', $date->month)
                    ->whereYear('user_memberships.end_date', $date->year)
                    ->count(),
            ]);
        }

        // Get package distribution
        $packageDistribution = MembershipPackage::withCount(['userMemberships as count'])
            ->having('count', '>', 0)
            ->get()
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'count' => $package->count,
                ];
            });

        // Get latest activities
        $latestActivities = Activity::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Get today's schedule
        $todaySchedule = TrainingSession::with(['trainer.user'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->get();

        $membershipStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $membershipStats->push([
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'new_members' => UserMembership::whereMonth('user_memberships.created_at', $date->month)
                    ->whereYear('user_memberships.created_at', $date->year)
                    ->count(),
                'expired_members' => UserMembership::where('status', 'expired')
                    ->whereMonth('user_memberships.end_date', $date->month)
                    ->whereYear('user_memberships.end_date', $date->year)
                    ->count(),
                'net_change' => UserMembership::whereMonth('user_memberships.created_at', $date->month)
                        ->whereYear('user_memberships.created_at', $date->year)
                        ->count() -
                    UserMembership::where('status', 'expired')
                        ->whereMonth('user_memberships.end_date', $date->month)
                        ->whereYear('user_memberships.end_date', $date->year)
                        ->count()
            ]);
        }

        // Add color coding for package distribution
        $packageDistribution = MembershipPackage::withCount(['userMemberships as count'])
            ->having('count', '>', 0)
            ->get()
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'count' => $package->count,
                    'color' => $this->generateColorForPackage($package->name)
                ];
            });


        return view('dashboard.index', compact(
            'activeMembers',
            'newMembersPercentage',
            'monthlyRevenue',
            'revenueGrowth',
            'todaySessions',
            'activeTrainers',
            'attendanceRate',
            'membershipStats',
            'packageDistribution',
            'latestActivities',
            'todaySchedule',
            'membershipStats',
            'packageDistribution'
        ));
    }

    private function generateColorForPackage($packageName)
    {

        $colors = [
            '#3498db', // Blue
            '#2ecc71', // Green
            '#f1c40f', // Yellow
            '#e74c3c', // Red
            '#9b59b6', // Purple
            '#1abc9c', // Turquoise
            '#34495e', // Dark Blue-Gray
        ];

        // Generate a consistent index based on package name
        $index = abs(crc32($packageName)) % count($colors);
        return $colors[$index];
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

