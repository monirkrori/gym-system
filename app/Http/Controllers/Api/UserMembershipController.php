<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\UserMembership;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

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
        $membership = UserMembership::findOrFail($id);

        if (!$membership) {
            return response()->json(['message' => 'User membership not found'], 404);
        }

        // Update the status
        $membership->status = $request->status;
        $membership->save();

        return response()->json(['message' => 'Membership status updated successfully', 'membership' => $membership], 200);
    }
}
