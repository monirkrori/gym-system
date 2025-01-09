<?php

namespace App\Http\Controllers\Api\member;

use App\Events\MembershipPackageRegistered;
use App\Events\MembershipRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\member\SubscribeToMembershipRequest;
use App\Http\Requests\member\SubscribeToPackageRequest;
use App\Models\MembershipPackage;
use App\Models\MembershipPlan;
use App\Services\Member\MembershipService;
use Illuminate\Http\JsonResponse;

class MembershipController extends Controller
{
    protected $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    /**
     * Subscribe to a membership plan
     *
     * @param SubscribeToMembershipRequest $request
     * @return JsonResponse
     */
    public function subscribeToMembership(SubscribeToMembershipRequest $request)
    {
        try {
            // Subscribe to the membership plan
            $userMembership = $this->membershipService->subscribe(
                auth()->id(),
                $request->plan_id
            );

            $user = auth()->user();

            $userMembership = MembershipPlan::find($request->plan_id);
            event(new MembershipRegistered($user, $userMembership));

            return $this->successResponse($userMembership, 'Successfully subscribed to the membership plan.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    public function subscribeToPackage(SubscribeToPackageRequest $request)
    {
        try {
            // Subscribe to the additional package
            $userMembership = $this->membershipService->subscribeToPackage(
                auth()->id(),
                $request->package_id
            );

            $user = auth()->user();
            $membershipPackage = MembershipPackage::find($request->package_id);
            event(new MembershipPackageRegistered($user, $membershipPackage));
            return $this->successResponse($membershipPackage, 'Successfully subscribed to the additional package.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

}
