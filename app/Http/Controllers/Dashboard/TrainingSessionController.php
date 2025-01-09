<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TrainingSessionRequest;
use App\Models\TrainingSession;
use App\Services\Dashboard\TrainingSessionService;
use Illuminate\Http\Request;


class TrainingSessionController extends Controller
{
    protected TrainingSessionService $trainingSessionService;

    public function __construct(TrainingSessionService $trainingSessionService)
    {
        $this->trainingSessionService = $trainingSessionService;
    }

    /**
     * Display a listing of the training sessions with filters and pagination.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status']);

        // Get sessions with filters and pagination using the service
        $data = $this->trainingSessionService->getSessionsWithFilters($filters);

        return view('sessions.index', $data);
    }

    /**
     * Show the details of a specific training session.
     */
    public function show(TrainingSession $session)
    {
        return view('sessions.show', compact('session'));
    }

    /**
     * Show the form for creating a new training session.
     */
    public function create()
    {
        $trainers = $this->trainingSessionService->getTrainers();
        $packages = $this->trainingSessionService->getMembershipPackages();
        return view('sessions.create', compact('trainers', 'packages'));
    }

    /**
     * Store a newly created training session.
     */
    public function store(TrainingSessionRequest $request)
    {
        $this->trainingSessionService->createSession($request->validated());
        return redirect()->route('admin.sessions.index')->with('success', 'Training session created successfully.');
    }

    /**
     * Show the form for editing the specified training session.
     */
    public function edit(TrainingSession $session)
    {
        $trainers = $this->trainingSessionService->getTrainers();
        $packages = $this->trainingSessionService->getMembershipPackages();
        return view('sessions.edit', compact('session', 'trainers', 'packages'));
    }

    /**
     * Update the specified training session.
     */
    public function update(TrainingSessionRequest $request, TrainingSession $session)
    {
        $this->trainingSessionService->updateSession($session, $request->validated());
        return redirect()->route('admin.sessions.index')->with('success', 'تم تعديل الجلسة بنجاح');
    }

    /**
     * Remove the specified training session.
     */
    public function destroy(TrainingSession $trainingSession)
    {
        $this->trainingSessionService->deleteSession($trainingSession);
        return redirect()->route('admin.sessions.index')->with('success', 'Training session deleted successfully.');
    }
}
