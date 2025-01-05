<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMembershipStatusRequest;
use App\Http\Resources\UserMembershipResource;
use App\Models\UserMembership;
use function response;

class UserMembershipController extends Controller
{
    public function updateStatus(UpdateMembershipStatusRequest $request, UserMembership $userMembership)
    {
        $userMembership->status = $request->status;
        $userMembership->save();

        return response()->json(new UserMembershipResource($userMembership), 200);
    }
}
