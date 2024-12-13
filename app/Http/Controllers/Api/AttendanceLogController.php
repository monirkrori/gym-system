<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AttendanceLogController extends Controller
{
    public function checkIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $attendanceLog = AttendanceLog::create([
            'user_id' => $request->user_id,
            'check_in' => now(),
            'status' => 'present',
            'notes' => $request->notes,
        ]);

        return response()->json($attendanceLog, 201);
    }

    public function checkOut(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Find the attendance log by ID
        $attendanceLog = AttendanceLog::find($id);

        if (!$attendanceLog) {
            return response()->json(['message' => 'Attendance log not found'], 404);
        }

        // Check if the user has already checked out
        if ($attendanceLog->check_out !== null) {
            return response()->json(['message' => 'User has already checked out'], 400);
        }

        // Update check out time
        $attendanceLog->check_out = now();
        $attendanceLog->notes = $request->notes ?? $attendanceLog->notes; // Update notes if provided
        $attendanceLog->save();

        return response()->json($attendanceLog, 200);
    }
}
