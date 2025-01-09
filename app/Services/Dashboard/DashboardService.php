<?php

namespace App\Services\Dashboard;

use App\Models\Activity;
use App\Models\AttendanceLog;
use App\Models\MembershipPackage;
use App\Models\Trainer;
use App\Models\TrainingSession;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use function collect;

/**
 * Service class for handling dashboard-related metrics and data.
 *
 * Provides methods for calculating various statistics, metrics, and
 * generating data visualizations for the admin dashboard.
 */
class DashboardService
{
    /**
     * Get the count of currently active members.
     *
     * @return int
     */
    public function getActiveMembersCount()
    {
        return UserMembership::where('status', 'active')->count();
    }

    /**
     * Get the count of active members who joined last month.
     *
     * @return int
     */
    public function getLastMonthMembersCount()
    {
        return UserMembership::where('status', 'active')
            ->where('created_at', '<=', Carbon::now()->subMonth())
            ->count();
    }

    /**
     * Get membership statistics for the last six months.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMembershipStatsForLastSixMonths()
    {
        $membershipStats = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // Count new memberships created during the month
            $newMembers = UserMembership::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            // Count memberships that expired during the month
            $expiredMembers = UserMembership::where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('end_date', [$startOfMonth, $endOfMonth])
                    ->orWhere(function ($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('status', 'expired')
                            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth]);
                    });
            })->count();

            $membershipStats->push([
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'new_members' => $newMembers,
                'expired_members' => $expiredMembers,
                'net_change' => $newMembers - $expiredMembers,
            ]);
        }

        return $membershipStats;
    }

    /**
     * Calculate metrics related to memberships, including growth percentage.
     *
     * @return array
     */
    public function calculateMembershipMetrics()
    {
        $activeMembers = $this->getActiveMembersCount();
        $lastMonthMembers = $this->getLastMonthMembersCount();

        $newMembersPercentage = $lastMonthMembers > 0
            ? round((($activeMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1)
            : 0;

        return [
            'activeMembers' => $activeMembers,
            'newMembersPercentage' => $newMembersPercentage,
        ];
    }

    /**
     * Calculate revenue for the current and last month.
     *
     * @return array
     */
    public function calculateMonthlyRevenue()
    {
        $monthlyRevenue = UserMembership::leftJoin('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->leftJoin('membership_plans', 'user_memberships.plan_id', '=', 'membership_plans.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->month)
            ->sum(DB::raw('COALESCE(membership_plans.price, 0) + COALESCE(membership_packages.price, 0)'));

        $lastMonthRevenue = UserMembership::leftJoin('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->leftJoin('membership_plans', 'user_memberships.plan_id', '=', 'membership_plans.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->subMonth()->month)
            ->sum(DB::raw('COALESCE(membership_plans.price, 0) + COALESCE(membership_packages.price, 0)'));

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return [
            'monthlyRevenue' => $monthlyRevenue,
            'revenueGrowth' => $revenueGrowth,
        ];
    }

    /**
     * Calculate metrics related to training sessions and trainers.
     *
     * @return array
     */
    public function calculateSessionAndTrainerMetrics()
    {
        $todaySessions = TrainingSession::whereDate('start_time', Carbon::today())->count();
        $activeTrainers = Trainer::where('status', 'available')->count();

        return [
            'todaySessions' => $todaySessions,
            'activeTrainers' => $activeTrainers,
        ];
    }

    /**
     * Calculate the attendance rate for the past 7 days.
     *
     * @return float
     */
    public function calculateAttendanceRate()
    {
        $totalAttendance = AttendanceLog::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        $totalExpectedAttendance = UserMembership::where('status', 'active')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now()->subDays(7))
                ->count() * 7;

        $attendanceRate = $totalExpectedAttendance > 0
            ? round(($totalAttendance / $totalExpectedAttendance) * 100, 1)
            : 0;

        return $attendanceRate;
    }

    /**
     * Get the distribution of membership packages with active memberships.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPackageDistribution()
    {
        return MembershipPackage::withCount('userMemberships')
            ->get()
            ->filter(function ($package) {
                return $package->user_memberships_count > 0; // Include only packages with memberships
            })
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'count' => $package->user_memberships_count,
                    'color' => $this->generateColorForPackage($package->name),
                ];
            });
    }

    /**
     * Generate a color for a membership package name.
     *
     * @param string $packageName
     * @return string
     */
    private function generateColorForPackage($packageName)
    {
        $colors = [
            '#3498db', '#2ecc71', '#f1c40f', '#e74c3c', '#9b59b6', '#1abc9c', '#34495e',
        ];

        $index = abs(crc32($packageName)) % count($colors);
        return $colors[$index];
    }

    /**
     * Get the latest activities for the dashboard.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestActivities()
    {
        return Activity::with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    /**
     * Get today's schedule of training sessions with trainers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTodaySchedule()
    {
        return TrainingSession::with(['trainer.user'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->get();
    }
}
