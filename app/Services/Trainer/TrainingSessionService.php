<?php

namespace App\Services\Trainer;

use App\Models\TrainingSession;
use Illuminate\Support\Facades\Auth;

class TrainingSessionService
{
    /**
     * Get all training sessions for the authenticated trainer.
     *
     * @return \Illuminate\Database\Eloquent\Collection List of training sessions belonging to the trainer.
     */
    public function getAllSessions()
    {
        // Retrieve all training sessions where the trainer is the authenticated user
        return TrainingSession::where('trainer_id', Auth::id())->get();
    }

    /**
     * Create a new training session for the authenticated trainer.
     *
     * @param array $data Data for the new training session.
     * @return TrainingSession The newly created training session instance.
     */
    public function createSession(array $data)
    {
        // Automatically associate the session with the authenticated trainer
        $data['trainer_id'] = Auth::id();
        return TrainingSession::create($data);
    }

    /**
     * Update an existing training session.
     *
     * @param TrainingSession $session The training session to be updated.
     * @param array $data Data to update the training session with.
     * @return TrainingSession The updated training session instance.
     */
    public function updateSession(TrainingSession $session, array $data)
    {
        // Update the specified training session with the provided data
        $session->update($data);
        return $session;
    }

    /**
     * Delete a training session.
     *
     * @param TrainingSession $session The training session to be deleted.
     * @return void
     */
    public function deleteSession(TrainingSession $session)
    {
        // Delete the specified training session
        $session->delete();
    }
}
