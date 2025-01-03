<?php

namespace App\Http\Controllers\Api\member;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use App\Models\Traits\ApiResponseTrait;
use App\Http\Requests\member\StoreAttendanceRequest;

class AttendanceController extends Controller
{
    use ApiResponseTrait;

    // Store member attendance.

    public function store(StoreAttendanceRequest $request)
    {
        // Check if the session is scheduled
        $trainingSession = TrainingSession::find($request->training_session_id);
        if ($trainingSession->status !== 'scheduled') {
            return $this->errorResponse('Training session is not available for attendance.');
        }

        // Check if the user exists 
        $user = User::find($request->user_id);
        if (!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        // Record attendance
        $attendance = AttendanceLog::create([
            'user_id' => $request->user_id,
            'training_session_id' => $request->training_session_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return $this->successResponse($attendance, 'Attendance recorded successfully.');
    }
//--------------------------------------------------------------------------------//
// for the trainer
    // Get the attendance log for a specific user.

    // public function getUserAttendance($userId)
    // {
    //     $attendance = AttendanceLog::where('user_id', $userId)->get();

    //     if ($attendance->isEmpty()) {
    //         return $this->errorResponse('No attendance records found for this user.');
    //     }

    //     return $this->successResponse($attendance, 'Attendance records retrieved successfully.');
    // }
}
