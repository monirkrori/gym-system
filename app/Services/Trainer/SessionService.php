<?php

namespace App\Services\Trainer;


use App\Models\TrainingSession;
use App\Models\Trainer;
use Illuminate\Support\Facades\Auth;

class SessionService
{
    public function getTrainerSessions($trainerId)
    {
        return TrainingSession::where('trainer_id', $trainerId)->get();
    }

    public function getSession($sessionId)
    {
        return TrainingSession::findOrFail($sessionId);
    }

    public function createSession($data)
    {
        $data['trainer_id'] = Auth::id();
        return TrainingSession::create($data);
    }

    public function updateSession($sessionId, $data)
    {
        $session = TrainingSession::findOrFail($sessionId);
        $session->update($data);
        return $session;
    }

    public function deleteSession($sessionId)
    {
        $session = TrainingSession::findOrFail($sessionId);
        $session->delete();
    }
}
