<?php

namespace App\Services\Dashboard;

use App\Models\TrainingSession;
use App\Models\Trainer;
use App\Models\MembershipPackage;
use App\Services\FilterService;

class TrainingSessionService
{
    protected FilterService $filterService;

    /**
     * Constructor for TrainingSessionService.
     *
     * @param FilterService $filterService Service to apply filters to queries.
     */
    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Retrieve a paginated list of training sessions with filters applied.
     *
     * @param array $filters Array of filters to apply.
     * @return array Contains paginated sessions and additional metrics.
     */
    public function getSessionsWithFilters(array $filters)
    {
        // Create a query for TrainingSession
        $query = TrainingSession::query();

        // Apply filters to the query using FilterService
        $this->filterService->applyFilters($query, $filters);

        // Retrieve paginated results
        $sessions = $query->paginate(10);

        // Gather additional metrics for display
        $totalSessions = TrainingSession::count(); // Total number of sessions
        $upcomingSessions = TrainingSession::where('created_at', '>', now())->count(); // Number of upcoming sessions

        return [
            'sessions' => $sessions, // Paginated session data
            'totalSessions' => $totalSessions, // Total sessions count
            'upcomingSessions' => $upcomingSessions // Upcoming sessions count
        ];
    }

    /**
     * Create a new training session.
     *
     * @param array $data Array of data for the new session.
     * @return TrainingSession The created training session instance.
     */
    public function createSession(array $data)
    {
        // Create a new training session
        return TrainingSession::create($data);
    }

    /**
     * Update an existing training session.
     *
     * @param TrainingSession $session The session instance to update.
     * @param array $data Array of updated data.
     * @return bool Whether the update was successful.
     */
    public function updateSession(TrainingSession $session, array $data)
    {
        // Update the given training session
        return $session->update($data);
    }

    /**
     * Delete a training session.
     *
     * @param TrainingSession $session The session instance to delete.
     * @return bool|null Whether the deletion was successful.
     */
    public function deleteSession(TrainingSession $session)
    {
        // Delete the specified training session
        return $session->delete();
    }

    /**
     * Retrieve all trainers for creating training sessions.
     *
     * @return \Illuminate\Database\Eloquent\Collection List of all trainers.
     */
    public function getTrainers()
    {
        // Get all trainer records
        return Trainer::all();
    }

    /**
     * Retrieve all membership packages for creating training sessions.
     *
     * @return \Illuminate\Database\Eloquent\Collection List of all membership packages.
     */
    public function getMembershipPackages()
    {
        // Get all membership package records
        return MembershipPackage::all();
    }
}
