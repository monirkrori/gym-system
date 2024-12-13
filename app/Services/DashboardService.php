<?php

    namespace App\Services;


    use App\Models\UserMembership;
    use App\Models\MembershipPackage;
    use App\Models\AttendanceLog;
    use App\Models\TrainingSession;
    use App\Models\Trainer;
    use App\Models\Activity;
    use App\Repositories\DashboardRepository;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;

    class DashboardService
    {
        protected $userMembershipRepository;

        public function __construct(DashboardRepository $userMembershipRepository)
        {
            $this->userMembershipRepository = $userMembershipRepository;
        }

        public function calculateMembershipMetrics()
        {
            $activeMembers = $this->userMembershipRepository->getActiveMembersCount();
            $lastMonthMembers = $this->userMembershipRepository->getLastMonthMembersCount();

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
            $monthlyRevenue = UserMembership::join('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
                ->join('membership_plans', 'user_memberships.plan_id', '=', 'membership_plans.id')
                ->whereMonth('user_memberships.created_at', Carbon::now()->month)
                ->sum(DB::raw('membership_packages.price + membership_plans.price'));

            $lastMonthRevenue = UserMembership::join('membership_packages', 'user_memberships.package_id', '=', 'membership_packages.id')
                ->join('membership_plans', 'user_memberships.plan_id' , '=' , 'membership_plans.id')
                ->whereMonth('user_memberships.created_at', Carbon::now()->month)
                ->sum(DB::raw('membership_packages.price + membership_plans.price'));

            $revenueGrowth = $lastMonthRevenue > 0
                ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
                : 0;

            return [
                'monthlyRevenue' => $monthlyRevenue,
                'revenueGrowth' => $revenueGrowth
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
            return MembershipPackage::withCount(['userMemberships as count'])
                ->having('count', '>', 0)
                ->get()
                ->map(function ($package) {
                    return [
                        'name' => $package->name,
                        'count' => $package->count,
                        'color' => $this->generateColorForPackage($package->name)
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
