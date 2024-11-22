<?php

namespace App\Http\Controllers;

use App\Models\Activitie;
use App\Models\User;
use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Models\UserMembership;
use App\Models\AttendanceLog;
use App\Models\MembershipPackage;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $latestActivities = Activitie::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Get today's schedule
        $todaySchedule = TrainingSession::with(['trainer.user'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->get();

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
            'todaySchedule'
        ));
    }
}
