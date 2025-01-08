<?php

namespace App\Services\Trainer;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;

class TrainingSessionService
{
    public function getAllSessions()
    {
        return TrainingSession::where('trainer_id', Auth::id())->get();
    }

    public function createSession(array $data)
    {
        $data['trainer_id'] = Auth::id();
        return TrainingSession::create($data);
    }

    public function updateSession(TrainingSession $session, array $data)
    {
        $session->update($data);
        return $session;
    }

    public function deleteSession(TrainingSession $session)
    {
        $session->delete();
    }
}
