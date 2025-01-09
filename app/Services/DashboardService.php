<?php

namespace App\Services;

use App\Models\UserMembership;
use App\Models\MembershipPackage;
use App\Models\AttendanceLog;
use App\Models\TrainingSession;
use App\Models\Trainer;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getActiveMembersCount()
    {
        return UserMembership::where('status', 'active')->count();
    }

    public function getLastMonthMembersCount()
    {
        return UserMembership::where('status', 'active')
            ->where('created_at', '<=', Carbon::now()->subMonth())
            ->count();
    }

    public function getMembershipStatsForLastSixMonths()
    {
        $membershipStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // حساب الأعضاء الجدد في هذا الشهر
            $newMembers = UserMembership::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            // حساب العضويات المنتهية بناءً على تاريخ الانتهاء
            $expiredMembers = UserMembership::where(function($query) use ($startOfMonth, $endOfMonth) {
                // العضويات التي تنتهي في هذا الشهر
                $query->whereBetween('end_date', [$startOfMonth, $endOfMonth])
                    // والعضويات التي حالتها منتهية يدوياً في هذا الشهر
                    ->orWhere(function($q) use ($startOfMonth, $endOfMonth) {
                        $q->where('status', 'expired')
                            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth]);
                    });
            })
                ->count();

            $membershipStats->push([
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'new_members' => $newMembers,
                'expired_members' => $expiredMembers,
                'net_change' => $newMembers - $expiredMembers
            ]);
        }
        return $membershipStats;
    }

    public function calculateMembershipMetrics()
    {
        $activeMembers = $this->getActiveMembersCount();
        $lastMonthMembers = $this->getLastMonthMembersCount();

        $newMembersPercentage = $lastMonthMembers > 0
            ? round((($activeMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1)
            : 0;

        return [
            'activeMembers' => $activeMembers,
            'newMembersPercentage' => $newMembersPercentage
        ];
    }

    public function calculateMonthlyRevenue()
    {
        $monthlyRevenue = UserMembership::leftJoin('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->leftJoin('membership_plans', 'user_memberships.plan_id', '=', 'membership_plans.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->month)
            ->sum(DB::raw('
            COALESCE(membership_plans.price, 0) + COALESCE(membership_packages.price, 0)
        '));

        $lastMonthRevenue = UserMembership::leftJoin('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
            ->leftJoin('membership_plans', 'user_memberships.plan_id', '=', 'membership_plans.id')
            ->whereMonth('user_memberships.created_at', Carbon::now()->subMonth()->month)
            ->sum(DB::raw('
            COALESCE(membership_plans.price, 0) + COALESCE(membership_packages.price, 0)
        '));

        $revenueGrowth = $lastMonthRevenue > 0
            ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        return [
            'monthlyRevenue' => $monthlyRevenue,
            'revenueGrowth' => $revenueGrowth,
        ];
    }


    public function calculateSessionAndTrainerMetrics()
    {
        $todaySessions = TrainingSession::whereDate('start_time', Carbon::today())->count();
        $activeTrainers = Trainer::where('status', 'available')->count();

        return [
            'todaySessions' => $todaySessions,
            'activeTrainers' => $activeTrainers
        ];
    }

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

    public function getPackageDistribution()
    {
        return MembershipPackage::withCount('userMemberships')
            ->get()
            ->filter(function ($package) {
                return $package->user_memberships_count > 0; // عرض الباقات التي لديها عضويات فقط
            })
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'count' => $package->user_memberships_count,
                    'color' => $this->generateColorForPackage($package->name),
                ];
            });
    }

    private function generateColorForPackage($packageName)
    {
        $colors = [
            '#3498db',
            '#2ecc71',
            '#f1c40f',
            '#e74c3c',
            '#9b59b6',
            '#1abc9c',
            '#34495e',
        ];

        $index = abs(crc32($packageName)) % count($colors);
        return $colors[$index];
    }

    public function getLatestActivities()
    {
        return Activity::with('user')
            ->latest()
            ->take(10)
            ->get();
    }

    public function getTodaySchedule()
    {
        return TrainingSession::with(['trainer.user'])
            ->whereDate('start_time', Carbon::today())
            ->orderBy('start_time')
            ->get();
    }
}
