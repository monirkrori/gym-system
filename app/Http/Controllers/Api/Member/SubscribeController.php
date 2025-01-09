<?php

namespace App\Http\Controllers\Api\Member;

use App\Events\MembershipPackageRegistered;
use App\Events\MembershipRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\SubscribeToMembershipRequest;
use App\Http\Requests\Member\SubscribeToPackageRequest;
use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Services\Member\SubscribeService;
use Illuminate\Http\JsonResponse;

class SubscribeController extends Controller
{
    // Declare a protected property for the subscribe service to handle subscription logic
    protected $subscribeService;

    /**
     * Constructor method to initialize the SubscribeService.
     *
     * @param SubscribeService $subscribeService The subscribe service instance.
     */
    public function __construct(SubscribeService $subscribeService)
    {
        // Assign the subscribe service to the controller property
        $this->subscribeService = $subscribeService;
    }

    /**
     * Subscribe to a membership plan.
     *
     * This method subscribes the authenticated user to a specific membership plan.
     * After successful subscription, an event is triggered to notify about the registration.
     *
     * @param SubscribeToMembershipRequest $request The validated request containing the plan_id.
     */
    public function subscribeToMembership(SubscribeToMembershipRequest $request)
    {
        try {
            // Subscribe the authenticated user to the membership plan
            $userMembership = $this->subscribeService->subscribe(
                auth()->id(), // Get the authenticated user's ID
                $request->plan_id // Get the membership plan ID from the request
            );

            $user = auth()->user();
            $userMembership = MembershipPlan::find($request->plan_id);

            // Trigger the MembershipRegistered event to notify about the new membership
            event(new MembershipRegistered($user, $userMembership));


            return $this->successResponse($userMembership, 'Successfully subscribed to the membership plan.');
        } catch (\Exception $e) {

            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Subscribe to an additional membership package.
     *
     * This method allows the user to subscribe to an additional package associated with their membership.
     * After successful subscription, an event is triggered to notify about the new package registration.
     *
     * @param SubscribeToPackageRequest $request The validated request containing the package_id.
     */
    public function subscribeToPackage(SubscribeToPackageRequest $request)
    {
        try {
            // Subscribe the authenticated user to the membership package
            $userMembership = $this->subscribeService->subscribeToPackage(
                auth()->id(), // Get the authenticated user's ID
                $request->package_id // Get the package ID from the request
            );

            $user = auth()->user();
            $membershipPackage = MembershipPackage::find($request->package_id);

            // Trigger the MembershipPackageRegistered event to notify about the new package subscription
            event(new MembershipPackageRegistered($user, $membershipPackage));

            return $this->successResponse($membershipPackage, 'Successfully subscribed to the additional package.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
