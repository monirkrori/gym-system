<?php

namespace App\Services\Member;

use App\Models\MembershipPlan;
use App\Models\UserMembership;
use App\Models\MembershipPackage;
use Carbon\Carbon;

class SubscribeService
{
    /**
     * Subscribe a user to a membership plan
     *
     * @param int $userId
     * @param int $planId
     * @param int|null $packageId
     * @return UserMembership
     */
    public function subscribe( $userId, $planId, $packageId = null)
    {
        // Get the membership plan
        $plan = MembershipPlan::findOrFail($planId);

        // Calculate start and end dates
        $startDate = Carbon::now();
        $endDate = $startDate->copy()->addMonths($plan->duration_month);

        // Calculate remaining sessions
        $remainingSessions = $this->calculateRemainingSessions($packageId);



        // Create or update the user membership
        return UserMembership::updateOrCreate(
            ['user_id' => $userId],
            [
                'plan_id' => $plan->id,
                'package_id' => $packageId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'remaining_sessions' => $remainingSessions,
                'status' => 'active',
            ]
        );

    }


    /**
     * Subscribe to an additional package
     *
     * @param int $userId
     * @param int $packageId
     * @return UserMembership
     */
    public function subscribeToPackage( $userId, $packageId)
    {
        // Get the user's active membership
        $userMembership = UserMembership::where('user_id', $userId)
            ->where('status', 'active')
            ->firstOrFail();

        // Get the package
        $package = MembershipPackage::findOrFail($packageId);

        // Update the remaining sessions
        $userMembership->remaining_sessions += $package->max_training_sessions;

        // Update the package_id
        $userMembership->package_id = $packageId;

        $userMembership->save();

        return $userMembership;
    }

    /**
     * Calculate remaining sessions based on the package
     *
     * @param int|null $packageId
     * @return int
     */
    private function calculateRemainingSessions($packageId)
    {
        if ($packageId) {
            $package = MembershipPackage::find($packageId);
            return $package ? $package->max_training_sessions : 1;
        }
        return 0; // Default value
    }
}
