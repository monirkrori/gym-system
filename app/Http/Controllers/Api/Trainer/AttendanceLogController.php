<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckInRequest;
use App\Http\Requests\CheckOutRequest;
use App\Http\Resources\AttendanceLogResource;
use App\Models\AttendanceLog;
use function now;
use function response;

class AttendanceLogController extends Controller
{
    public function checkIn(CheckInRequest $request)
    {
        $attendanceLog = AttendanceLog::create([
            'user_id' => $request->user_id,
            'check_in' => now(),
            'status' => 'present',
            'notes' => $request->notes,
        ]);

        return response()->json(new AttendanceLogResource($attendanceLog), 201);
    }

    public function checkOut(CheckOutRequest $request, AttendanceLog $attendanceLog)
    {
        // Check if the user has already checked out
        if ($attendanceLog->check_out !== null) {
            return response()->json(['message' => 'User has already checked out'], 400);
        }

        // Update check out time
        $attendanceLog->check_out = now();
        $attendanceLog->notes = $request->notes ?? $attendanceLog->notes; // Update notes if provided
        $attendanceLog->save();

        return response()->json(new AttendanceLogResource($attendanceLog), 200);
    }
}
