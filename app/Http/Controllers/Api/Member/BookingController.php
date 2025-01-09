<?php

namespace App\Http\Controllers\Api\Member;

use Carbon\Carbon;
use App\Models\Booking;
use App\Models\UserMembership;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Member\BookSessionRequest;
use App\Http\Requests\Member\CancelSessionRequest;

class BookingController extends Controller
{
    /**
     * Book a training session for the authenticated user.
     *
     * @param BookSessionRequest $request The validated request containing the session ID.
     */
    public function bookSession(BookSessionRequest $request)
    {
        $user = auth()->user();
        $session = TrainingSession::findOrFail($request->session_id);

        // Check if the session has available capacity
        if ($session->current_capacity >= $session->max_capacity) {
            return $this->errorResponse('Session is full. Please select another time.', 400);
        }

        // Verify the user has an active membership
        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$userMembership) {
            return $this->errorResponse('No active membership found.', 400);
        }

        // Check if the user has remaining sessions
        if ($userMembership->remaining_sessions <= 0) {
            return $this->errorResponse('You do not have enough remaining sessions.', 400);
        }

        // Verify the session's difficulty level matches the user's membership level
        if ($session->allowed_membership_levels !== $userMembership->package->difficulty_level) {
            return $this->errorResponse('This session is not suitable for your membership level.', 400);
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'session_id' => $session->id,
            'status' => 'booked',
        ]);

        // Update session capacity and user's remaining sessions
        $session->increment('current_capacity');
        $userMembership->decrement('remaining_sessions');

        return $this->successResponse($booking, 'Session booked successfully.');
    }

    /**
     * Cancel a training session booking for the authenticated user.
     *
     * @param CancelSessionRequest $request The validated request containing the session ID.

     */
    public function cancelSession(CancelSessionRequest $request)
    {
        $user = auth()->user();
        $session = TrainingSession::findOrFail($request->session_id);

        // Verify the user has a booking for the session
        $booking = Booking::where('user_id', $user->id)
            ->where('session_id', $session->id)
            ->where('status', 'booked')
            ->first();

        if (!$booking) {
            return $this->errorResponse('You have no booking for this session.', 400);
        }

        // Check if the session start time is at least 1 day away
        $currentTime = now();
        $sessionStartTime = Carbon::parse($session->start_time);

        if ($sessionStartTime->diffInDays($currentTime) < 1) {
            return $this->errorResponse('You can only cancel a session at least 1 day before.', 400);
        }

        // Update session capacity and user's remaining sessions
        $session->decrement('current_capacity');
        $userMembership = UserMembership::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if ($userMembership) {
            $userMembership->increment('remaining_sessions');
        }

        // Mark the booking as cancelled
        $booking->update(['status' => 'cancelled']);

        return $this->successResponse(null, 'Session booking cancelled successfully.');
    }

    /**
     * Retrieve the authenticated user's booking history.
     *
     * @param Request $request The incoming request.
     */
    public function getBookingHistory(Request $request)
    {
        $user = auth()->user();

        // Fetch and format the booking history
        $bookings = Booking::where('user_id', $user->id)
            ->with('session')
            ->orderBy('booked_at', 'desc')
            ->get();

        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'session_name' => $booking->session->name,
                'start_time' => $booking->session->start_time,
                'status' => $booking->status,
                'booked_at' => $booking->booked_at ? Carbon::parse($booking->booked_at)->format('Y-m-d H:i:s') : null,
                'completed_at' => $booking->completed_at ? Carbon::parse($booking->completed_at)->format('Y-m-d H:i:s') : null,
            ];
        });

        return $this->successResponse($formattedBookings, 'Booking history retrieved successfully.');
    }

    /**
     * Retrieve a usage report for the authenticated user.
     *
     * @param Request $request The incoming request.
     */
    public function getUsageReport(Request $request)
    {
        $user = auth()->user();

        // Calculate booking statistics
        $totalBooked = Booking::where('user_id', $user->id)->where('status', 'booked')->count();
        $totalCompleted = Booking::where('user_id', $user->id)->where('status', 'completed')->count();
        $totalCancelled = Booking::where('user_id', $user->id)->where('status', 'cancelled')->count();

        return $this->successResponse([
            'total_booked' => $totalBooked,
            'total_completed' => $totalCompleted,
            'total_cancelled' => $totalCancelled,
        ], 'Usage report retrieved successfully.');
    }
}
