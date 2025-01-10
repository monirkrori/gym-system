<?php

namespace App\Services\Dashboard;

use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use App\Services\FilterService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserMembershipService
{
    protected FilterService $filterService;

    /**
     * Constructor for UserMembershipService.
     *
     * @param FilterService $filterService Service for applying query filters.
     */
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Get membership statistics, including total, active, and expired memberships.
     *
     * @return array Array containing membership statistics.
     */
    public function getMembershipStatistics()
    {
        return [
            'total' => UserMembership::count(), // Total memberships
            'active' => UserMembership::where('status', 'active')->count(), // Active memberships
            'expired' => UserMembership::where('status', 'expired')->count(), // Expired memberships
        ];
    }

    /**
     * Retrieve a paginated list of user memberships with filters applied.
     *
     * @param int $perPage Number of items per page.
     * @param array $filters Filters to apply to the query.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Paginated memberships.
     */
    public function getMembershipsPaginated($perPage = 10, $filters = [])
    {
        // Load related models for user, package, and plan
        $query = UserMembership::with(['user', 'package', 'plan']);

        // Apply filters using the FilterService
        $this->filterService->applyFilters($query, $filters);

        // Sort memberships by creation date in descending order
        $query->orderBy('created_at', 'desc');

        // Return paginated results
        return $query->paginate($perPage);
    }

    /**
     * Create a new membership for a user.
     *
     * @param array $data Membership data, including user ID, package, plan, and start date.
     * @return UserMembership The created membership instance.
     * @throws \Exception If the user already has a membership.
     */
    public function createMembership(array $data)
    {
        // Fetch the user, package, and plan models
        $user = User::findOrFail($data['user_id']);
        $package = isset($data['package_id']) ? MembershipPackage::findOrFail($data['package_id']) : null;
        $plan = MembershipPlan::findOrFail($data['plan_id']);

        // Calculate the end date based on the plan duration
        $endDate = Carbon::parse($data['start_date'])->addMonths($plan->duration_month);

        // Check if the user is a trainer and if they already have a membership
        $trainer = Trainer::where('user_id', $user->id)->first();
        $existingMembership = UserMembership::where('user_id', $user->id)->first();

        if ($existingMembership) {
            throw new \Exception('The user is already a member of the club.');
        }

        // Prepare membership data for creation
        $membershipData = [
            'user_id' => $user->id,
            'plan_id' => $data['plan_id'],
            'package_id' => $package ? $package->id : null,
            'start_date' => $data['start_date'],
            'end_date' => $endDate,
            'status' => 'active',
            'remaining_sessions' => $package ? $package->max_training_sessions : 0,
        ];

        // Perform transactional operations
        DB::transaction(function () use ($membershipData, $trainer, $user) {
            if ($trainer) {
                // Remove trainer role and assign member role if applicable
                $trainer->delete();
                $user->removeRole('trainer');
                $user->assignRole('member');
            }
        });

        // Create and return the new membership
        return UserMembership::create($membershipData);
    }

    /**
     * Update an existing membership with new data.
     *
     * @param UserMembership $membership The membership instance to update.
     * @param array $data Array of updated data.
     * @return UserMembership Updated membership instance.
     */
    public function updateMembership(UserMembership $membership, array $data)
    {
        $membership->update($data); // Update the membership
        return $membership; // Return the updated membership
    }

    /**
     * Delete an existing membership.
     *
     * @param UserMembership $membership The membership instance to delete.
     * @return void
     */
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
