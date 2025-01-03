<?php

namespace App\Http\Controllers\Api\member;

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
        $user = Auth::user();

    // Retrieve sessions available for the user based on their membership level
    $sessions = TrainingSession::where('status', 'scheduled')
        ->whereColumn('current_capacity', '<', 'max_capacity')
        ->where('start_time', '>', now())  // Only future sessions
        ->get();

    return $this->successResponse($sessions, 'Available sessions retrieved successfully.');
    }
//--------------------------------------------------------------------------------//

    public function show($id)
    {
        // Find the session by ID
        $session = TrainingSession::find($id);  // Using find() instead of findOrFail to allow for custom error handling

        // Check if the session was found
        if (!$session) {
            return $this->errorResponse('Training session not found', 404);  // Using errorResponse from the trait
        }

        // Return the status of the found session
        return $this->successResponse($session, 'Training session retrieved successfully.');
    }



}
