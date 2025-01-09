<?php

namespace App\Http\Controllers\Api\Member;

use App\Services\FilterService;
use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetTrainingSessionController extends Controller
{
    // Declare a protected property for the filter service to handle filtering logic
    protected $filterService;

    /**
     * Constructor method to initialize the FilterService.
     *
     * @param FilterService $filterService The filter service instance.
     */
    public function __construct(FilterService $filterService)
    {
        // Assign the filter service to the controller property
        $this->filterService = $filterService;
    }

    /**
     * Retrieve a list of available training sessions for the member.
     *
     * This method allows clients to filter sessions based on search query, status, start time, and capacity.
     * It ensures only future training sessions are displayed and the current capacity is less than the maximum capacity.
     *
     * @param Request $request The request containing filter parameters.
     */
    public function listSessions(Request $request)
    {

        $filters = $request->only(['search', 'status', 'start_time', 'sort_by_capacity']);

        // Build the query for fetching scheduled training sessions that are not full and start in the future
        $query = TrainingSession::where('status', 'scheduled')
            ->whereColumn('current_capacity', '<', 'max_capacity') // Ensure there is capacity available
            ->where('start_time', '>', now()); // Only fetch future sessions

        // Apply any additional filters to the query using the filter service
        $this->filterService->applyFilters($query, $filters);

        $sessions = $query->get();

        return $this->successResponse($sessions, 'Available sessions retrieved successfully.');
    }

    //--------------------------------------------------------------------------------//

    /**
     * Retrieve details of a specific training session by its ID.
     *
     * This method fetches the details of a session based on the session ID.
     * If the session is not found, an error response is returned.
     *
     * @param int $id The ID of the training session to be retrieved.
     */
    public function show($id)
    {
        // Attempt to find the training session by its ID
        $session = TrainingSession::find($id);

        // If the session is not found, return an error response with a 404 status
        if (!$session) {
            return $this->errorResponse('Training session not found', 404);
        }

        // If the session is found, return a successful response with the session details
        return $this->successResponse($session, 'Training session retrieved successfully.');
    }
}
