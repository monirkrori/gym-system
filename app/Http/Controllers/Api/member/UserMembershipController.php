<?php

namespace App\Http\Controllers\Api\member;

use App\Models\UserMembership;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class UserMembershipController extends Controller
{
     use ApiResponseTrait;
        // Subscribe to a membership package
        public function subscribeToMembership(Request $request)
        {

            $user = Auth::user();

            // Create or update user membership
            $userMembership = UserMembership::updateOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'start_date' => now(),
                    'end_date' => now()->addDays("duration_days"),
                    'status' => 'active',
                ]
            );

            return $this->successResponse($userMembership, 'Successfully subscribed to the membership.');
        }

}
