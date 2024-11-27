<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\UserMembership;
use App\Models\MembershipPackage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubscribeMembershipRequest;

class UserMembershipController extends Controller
{
        // Subscribe to a membership package
        public function subscribeToMembership(SubscribeMembershipRequest $request)
        {
            $user = Auth::user();

            // Find the selected package by ID
            $membershipPackage = MembershipPackage::find($request->membership_package_id);

            if (!$membershipPackage) {
                return $this->errorResponse('Membership package not found.');
            }

            // Create or update user membership
            $userMembership = UserMembership::updateOrCreate(
                ['user_id' => $user->id, 'package_id' => $membershipPackage->id],
                ['start_date' => now(), 'end_date' => now()->addDays($membershipPackage->duration_days), 'status' => 'active']
            );

            // Assuming subscription logic is handled here
            return $this->succsessResponse($userMembership, 'Successfully subscribed to the membership.');
        }

}
