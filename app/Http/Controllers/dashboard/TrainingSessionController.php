<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\TrainingSessionRequest;
use App\Models\MembershipPackage;
use App\Models\Trainer;
use App\Models\TrainingSession;

use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function index()
    {
        // إجمالي عدد الجلسات
        $totalSessions = TrainingSession::count();
        $sessions = TrainingSession::paginate(10);

        $upcomingSessions = TrainingSession::where('created_at', '>', now())->count();

        return view('sessions.index', [
            'totalSessions' => $totalSessions,
            'upcomingSessions' => $upcomingSessions,
            'sessions' => $sessions
        ]);
    }

    public function create()
    {
        $trainers = Trainer::all();
        $packages = MembershipPackage::all();
        return view('sessions.create', compact('packages', 'trainers'));
    }

    public function store(TrainingSessionRequest $request)
    {

        TrainingSession::create($request->validated());
        return redirect()->route('admin.sessions.index')->with('success', 'Training session created successfully.');
    }


    public function edit(TrainingSession $session)
    {

        $trainers = Trainer::all();
        $packages = MembershipPackage::all();
        return view('sessions.edit', compact('session', 'packages', 'trainers'));
    }

    public function update(TrainingSessionRequest $request, TrainingSession $session)
    {
        $session->update($request->validated());
        return redirect()->route('admin.sessions.index')->with('success', 'تم تعديل الجلسة بنجاح');
    }

    public function destroy(TrainingSession $trainingSession)
    {
        $trainingSession->delete();
        return redirect()->route('admin.sessions.index')->with('success', 'Training session deleted successfully.');
    }
}
