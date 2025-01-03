<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMembershipStatusRequest;
use App\Http\Resources\UserMembershipResource;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserMembershipController extends Controller
{
    public function updateStatus(UpdateMembershipStatusRequest $request, UserMembership $userMembership)
    {
        $userMembership->status = $request->status;
        $userMembership->save();

        return response()->json(new UserMembershipResource($userMembership), 200);
    }
}
