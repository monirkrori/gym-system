<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMembership;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserMembershipController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,expired,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the membership by ID
        $membership = UserMembership::find($id);

        if (!$membership) {
            return response()->json(['message' => 'User membership not found'], 404);
        }

        // Update the status
        $membership->status = $request->status;
        $membership->save();

        return response()->json(['message' => 'Membership status updated successfully', 'membership' => $membership], 200);
    }
}
