<?php

namespace App\Http\Controllers\Api\member;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\UserMembership;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\member\BookSessionRequest;
use App\Http\Requests\member\CancelSessionRequest;

class BookingController extends Controller
{

//  Book a training session for a user

    public function bookSession(BookSessionRequest $request)
    {
        $user = auth()->user(); // Get the authenticated user
        $session = TrainingSession::findOrFail($request->session_id);

        // Check if the session has available capacity
        if ($session->current_capacity >= $session->max_capacity) {
            return $this->errorResponse('Session is full. Please select another time', 400);
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

         // Update the session's current capacity
        $session->increment('current_capacity');

         // Reduce the user's remaining sessions
         $userMembership->decrement('remaining_sessions');


        return $this->successResponse($booking, 'Session booked successfully');
    }
//--------------------------------------------------------------------------------//

    //Cancel a training session booking for a user

    public function cancelSession(CancelSessionRequest $request)
    {
        $user = auth()->user();
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

        return $this->successResponse(null, 'Session booking cancelled successfully');
    }
//--------------------------------------------------------------------------------//

    //get booking history for a user

    public function getBookingHistory(Request $request)
    {
        $user = auth()->user();

        // Retrieve the user's booking history
        $bookings = Booking::where('user_id', $user->id)
            ->with('session')
            ->orderBy('booked_at', 'desc') // Sort by booking date in descending order
            ->get();

        // customize the data you want to return
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'session_name' => $booking->session->name,
                'start_time' => $booking->session->start_time,
                'status' => $booking->status,
                'booked_at' => $booking->booked_at ? Carbon::parse($booking->booked_at)->format('Y-m-d H:i:s') : null,  // Manually parse if not null
                'completed_at' => $booking->completed_at ? Carbon::parse($booking->completed_at)->format('Y-m-d H:i:s') : null,
            ];
        });

        return $this->successResponse($formattedBookings, 'Booking history retrieved successfully.');
    }

//--------------------------------------------------------------------------------//

    //counting the bookings and checking their statuses

    public function getUsageReport(Request $request)
    {
        $user = auth()->user();

        // Total booked sessions (ignoring the cancelled ones)
        $totalBooked = Booking::where('user_id', $user->id)
            ->where('status', 'booked')
            ->count();

        // Total completed sessions
        $totalCompleted = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Total cancelled sessions
        $totalCancelled = Booking::where('user_id', $user->id)
            ->where('status', 'cancelled')
            ->count();

        return $this->successResponse([
            'total_booked' => $totalBooked,
            'total_completed' => $totalCompleted,
            'total_cancelled' => $totalCancelled,
        ], 'Usage report retrieved successfully.');
    }
}
