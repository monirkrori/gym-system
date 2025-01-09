<?php

namespace App\Services;

use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserMembershipService
{

    public function getMembershipStatistics()
    {
        return [
            'total' => UserMembership::count(),
            'active' => UserMembership::where('status', 'active')->count(),
            'expired' => UserMembership::where('status', 'expired')->count(),
        ];
    }

    public function getMembershipsPaginated($perPage = 10)
    {
        return UserMembership::with(['user', 'package', 'plan'])->paginate($perPage);
    }

    public function createMembership(array $data)
    {
        $user = User::findOrFail($data['user_id']);
        $package = MembershipPackage::findOrFail($data['package_id']);
        $plan = MembershipPlan::findOrFail($data['plan_id']);
        $endDate = Carbon::parse($data['start_date'])->addMonths($plan->duration_month);

        $trainer = Trainer::where('user_id', $user->id)->first();
        $existingMembership = UserMembership::where('user_id', $user->id)->first();

        if ($existingMembership) {
            throw new \Exception('العضو موجود مسبقاً في النادي');
        }

        $membershipData = [
            'user_id' => $user->id,
            'plan_id' => $data['plan_id'],
            'package_id' =>$package->id,
            'start_date' => $data['start_date'],
            'end_date' => $endDate,
            'status' => 'active',
            'remaining_sessions' => $package->max_training_sessions,
        ];

        DB::transaction(function () use ($membershipData, $trainer, $user) {
            if ($trainer) {
                $trainer->delete();
                $user->removeRole('trainer');
                $user->assignRole('member');
            }

        });

        return UserMembership::create($membershipData);
    }


    public function updateMembership(UserMembership $membership, array $data)
    {
        $membership->update($data);
        return $membership;
    }

    public function deleteMembership(UserMembership $membership)
    {
        $membership->delete();
    }

    public function restoreMembership($id)
    {
        $membership = UserMembership::withTrashed()->findOrFail($id);

        $membership->restore();
    }

    public function forceDeleteMembership($id)
    {
        $membership = UserMembership::withTrashed()->findOrFail($id); 

        $membership->forceDelete();
    }

    public function deletedMemberships()
    {
        $deletedMemberships = UserMembership::onlyTrashed()->get();
        $package = MembershipPackage::get();
        $plan = MembershipPlan::get();
        return [
            'deletedMemberships' => $deletedMemberships,
            'package' => $package,
            'plan' => $plan
        ];
    }

}
