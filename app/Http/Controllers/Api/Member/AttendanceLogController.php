<?php

namespace App\Http\Controllers\Api\Member;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreAttendanceRequest;

class AttendanceLogController extends Controller
{

    /**
     * Store member attendance.
     */
    public function store(StoreAttendanceRequest $request)
    {
        $user = auth()->user();

        if (!$user) {
            return $this->errorResponse('Unauthorized.', 401);
        }

        // Check if the training session exists and is scheduled
        $trainingSession = TrainingSession::find($request->training_session_id);
        if (!$trainingSession || $trainingSession->status !== 'scheduled') {
            return $this->errorResponse('Training session is not available for attendance.', 404);
        }

        // Check if attendance already exists for this session and user
        $existingAttendance = AttendanceLog::where('user_id', $request->user->id)
            ->where('training_session_id', $request->training_session_id)
            ->first();

        if ($existingAttendance) {
            return $this->errorResponse('Attendance already recorded for this session.', 409);
        }

        // Record attendance
        $attendance = AttendanceLog::create([
            'user_id' => $request->user->id,
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
