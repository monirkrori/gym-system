<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\SessionResource;
use App\Http\Resources\AttendanceResource;

class TrainerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:trainer');
    }

    // ... (الدوال السابقة تبقى كما هي)

    /**
     * تسجيل وصول العضو (Check-in)
     */
    public function checkIn(Request $request, Session $session)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:255'
        ]);

        if ($session->trainer_id !== Auth::id()) {
            return response()->json(['message' => 'غير مصرح لك بتسجيل الحضور لهذه الجلسة'], 403);
        }

        // التحقق من أن الجلسة جارية
        if ($session->status !== 'ongoing') {
            return response()->json(['message' => 'يمكن تسجيل الوصول فقط للجلسات الجارية'], 400);
        }

        $attendance = Attendance::updateOrCreate(
            [
                'session_id' => $session->id,
                'member_id' => $request->member_id,
                'date' => now()->toDateString()
            ],
            [
                'status' => 'present',
                'check_in_time' => now(),
                'notes' => $request->notes
            ]
        );

        return new AttendanceResource($attendance);
    }

    /**
     * تسجيل مغادرة العضو (Check-out)
     */
    public function checkOut(Request $request, Session $session)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:255'
        ]);

        if ($session->trainer_id !== Auth::id()) {
            return response()->json(['message' => 'غير مصرح لك بتسجيل المغادرة لهذه الجلسة'], 403);
        }

        $attendance = Attendance::where([
            'session_id' => $session->id,
            'member_id' => $request->member_id,
            'date' => now()->toDateString()
        ])->first();

        if (!$attendance) {
            return response()->json(['message' => 'لم يتم تسجيل وصول لهذا العضو اليوم'], 404);
        }

        if ($attendance->check_out_time) {
            return response()->json(['message' => 'تم تسجيل مغادرة هذا العضو مسبقاً'], 400);
        }

        $attendance->update([
            'check_out_time' => now(),
            'notes' => $request->notes ? $attendance->notes . "\nCheck-out: " . $request->notes : $attendance->notes
        ]);

        return new AttendanceResource($attendance);
    }

    /**
     * عرض تقرير الوصول والمغادرة للجلسة
     */
    public function checkInOutReport(Session $session)
    {
        if ($session->trainer_id !== Auth::id()) {
            return response()->json(['message' => 'غير مصرح لك بعرض تقرير هذه الجلسة'], 403);
        }

        $attendance = $session->attendance()
            ->with('member')
            ->whereNotNull('check_in_time')
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->get();

        return AttendanceResource::collection($attendance);
    }
}
