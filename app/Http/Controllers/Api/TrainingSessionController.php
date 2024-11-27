<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\ApiResponseTrait;

class TrainingSessionController extends Controller
{
    use ApiResponseTrait;

    // Display available training sessions for the member
    public function listSessions(Request $request)
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get the available training sessions
        $sessions = TrainingSession::where('status', 'scheduled')
            ->whereIn('allowed_membership_levels', [$user->membership_level]) 
            ->get();

        return $this->succsessResponse($sessions, 'Available sessions retrieved successfully.');
    }


}
