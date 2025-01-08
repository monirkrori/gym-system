<?php

namespace App\Http\Controllers\Api\Trainer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\TrainingSessionRequest;
use App\Models\TrainingSession;
use App\Services\Trainer\TrainingSessionService;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    protected $trainingSessionService;

    public function __construct(TrainingSessionService $trainingSessionService)
    {
        $this->trainingSessionService = $trainingSessionService;
    }

    public function index()
    {
        $sessions = $this->trainingSessionService->getAllSessions();
        return response()->json($sessions);
    }

    public function store(TrainingSessionRequest $request)
    {
        $session = $this->trainingSessionService->createSession($request->validated());
        return response()->json($session, 201);
    }

    public function update(TrainingSessionRequest $request, TrainingSession $session)
    {
        $session = $this->trainingSessionService->updateSession($session, $request->validated());
        return response()->json($session);
    }

    public function destroy(TrainingSession $session)
    {
        $this->trainingSessionService->deleteSession($session);
        return response()->json(null, 204);
    }
}
