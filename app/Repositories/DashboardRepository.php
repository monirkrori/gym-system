<?php

namespace App\Repositories;

use App\Models\UserMembership;
use Carbon\Carbon;

class DashboardRepository
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
            $membershipStats->push([
                'month' => $date->format('M'),
                'year' => $date->format('Y'),
                'new_members' => UserMembership::whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'expired_members' => UserMembership::where('status', 'expired')
                    ->whereMonth('end_date', $date->month)
                    ->whereYear('end_date', $date->year)
                    ->count(),
                'net_change' => UserMembership::whereMonth('created_at', $date->month)
                        ->whereYear('created_at', $date->year)
                        ->count() -
                    UserMembership::where('status', 'expired')
                        ->whereMonth('end_date', $date->month)
                        ->whereYear('end_date', $date->year)
                        ->count()
            ]);
        }
        return $membershipStats;
    }
}
