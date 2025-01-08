<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainingSession;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Auth;

class TrainerAttendanceController extends Controller
{
    /**
     * Get attendance logs for a specific training session.
     */
    public function getSessionAttendance($trainingSessionId)
    {
        // Check if the training session exists
        $trainingSession = TrainingSession::findOrFail($trainingSessionId);

        // Retrieve all attendance logs for the session
        $attendanceLogs = AttendanceLog::where('training_session_id', $trainingSessionId)
            ->with('user:id,name,email') // Load related user data
            ->get();

        if ($attendanceLogs->isEmpty()) {
            return $this->errorResponse('No attendance records found for this session.', 404);
        }

        return $this->successResponse($attendanceLogs, 'Attendance records retrieved successfully.');
    }

    /**
     * Display attendance report for a specific session.
     */
    public function getAttendanceReport($trainingSessionId)
    {
        // Check if the training session exists
        $session = TrainingSession::findOrFail($trainingSessionId);

        // Ensure the current trainer is the trainer of the session
        if ($session->trainer_id !== Auth::id()) {
            return $this->errorResponse('You are not authorized to access this session report.', 403);
        }

        // Get attendance details
        $attendanceDetails = AttendanceLog::where('training_session_id', $trainingSessionId)
            ->with('user:id,name,email') // Load related user data
            ->get()
            ->map(function ($record) {
                return [
                    'user_id' => $record->user_id,
                    'user_name' => $record->user->name,
                    'check_in' => $record->check_in,
                    'check_out' => $record->check_out,
                    'status' => $record->status,
                    'notes' => $record->notes,
                ];
            });

        // Calculate statistics
        $totalMembers = $attendanceDetails->count();
        $present = $attendanceDetails->where('status', 'present')->count();
        $absent = $totalMembers - $present;

        // Build the report
        $report = [
            'session_id' => $session->id,
            'session_name' => $session->name,
            'total_members' => $totalMembers,
            'present' => $present,
            'absent' => $absent,
            'attendance_details' => $attendanceDetails,
        ];

        return $this->successResponse($report, 'Attendance report retrieved successfully.');
    }

}
