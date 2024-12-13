<?php

namespace App\Http\Controllers\Api\member;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\UserMembership;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\ApiResponseTrait;
use App\Http\Requests\member\BookSessionRequest;
use App\Http\Requests\member\CancelSessionRequest;

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
//--------------------------------------------------------------------------------//

    public function show($id)
    {
        // Find the session by ID
        $session = TrainingSession::findOrFail($id);

        if (!$session) {
            return response()->json(['message' => 'Training session not found'], 404);
        }

        return response()->json(['status' => $session->status]);
    }
//--------------------------------------------------------------------------------//

    //  Book a training session for a user

    public function bookSession(BookSessionRequest $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $session = TrainingSession::findOrFail($request->session_id);

        // Check if the session has available capacity
        if ($session->current_capacity >= $session->max_capacity) {
            return $this->errorResponse('Session is already full', 400);
        }

        // Check if the user has an active membership
        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userMembership) {
            return $this->errorResponse('No active membership found', 400);
        }

        // Check if user has remaining sessions
        if ($userMembership->remaining_sessions <= 0) {
            return $this->errorResponse('You do not have enough remaining sessions', 400);
        }

        // Check if the session's difficulty level matches the user's membership level
        if ($session->allowed_membership_levels !== $userMembership->package->difficulty_level) {
            return $this->errorResponse('This session is not suitable for your membership level', 400);
        }

        // Create a booking for the user
        $booking = Booking::create([
            'user_id' => $user->id,
            'session_id' => $session->id,
            'status' => 'booked',
        ]);

        // Reduce the user's remaining sessions
        $userMembership->remaining_sessions -= 1;
        $userMembership->save();

        // Update the session's current capacity
        $session->current_capacity += 1;
        $session->save();

        return $this->succsessResponse($booking, 'Session booked successfully');
    }
//--------------------------------------------------------------------------------//

    //Cancel a training session booking for a user

    public function cancelSession(CancelSessionRequest $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $session = TrainingSession::with('package')->findOrFail($request->session_id);;

        // Check if the user has a booking for the session
        $booking = Booking::where('user_id', $user->id)
            ->where('session_id', $session->id)
            ->where('status', 'booked')
            ->first();

        if (!$booking) {
            return $this->errorResponse('You have no booking for this session', 400);
        }

        // Check if the session's start time is at least 1 day from now
        $currentTime = now();
        $sessionStartTime = Carbon::parse($session->start_time); // Convert session start time to a Carbon instance

        // If the session starts within 1 day, prevent cancellation
        if ($sessionStartTime->diffInDays($currentTime) < 1) {
            return $this->errorResponse('You can only cancel a session at least 1 day befor', 400);
        }

        // Update the user's remaining sessions
        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($userMembership) {
            $userMembership->remaining_sessions += 1;
            $userMembership->save();
        }

        // Update the session's current capacity
        $session->current_capacity -= 1;
        $session->save();

        // Cancel the booking
        $booking->status = 'cancelled';
        $booking->save();

        return $this->succsessResponse(null, 'Session booking cancelled successfully');
    }
}
