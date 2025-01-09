<?php

namespace App\Http\Controllers\Api\Member;

use App\Models\Trainer;
use App\Services\FilterService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class GetTrainerController extends Controller
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
     * Retrieve a list of trainers based on applied filters.
     *
     * This method allows clients to filter trainers by search query, status, average rating,
     * rating sorting order, and associated plan.
     *
     * @param Request $request The request containing the filter parameters.
     * @return \Illuminate\Http\JsonResponse A JSON response with the list of filtered trainers.
     */
    public function listTrainer(Request $request)
    {
        // Extract filter parameters from the incoming request
        $filters = $request->only(['search', 'status', 'rating_avg', 'sort_by_rating', 'plan_id']);

        // Initialize the query to fetch trainers who are available
        $query = Trainer::where('status', 'available'); // Or use 'is_available' based on the database schema

        // Apply any filters using the FilterService
        $this->filterService->applyFilters($query, $filters);

        // Execute the query and retrieve the filtered trainers
        $trainers = $query->get();

        // Return a successful response with the retrieved list of trainers
        return $this->successResponse($trainers, 'Available trainers retrieved successfully.');
    }

    //--------------------------------------------------------------------------------//

    /**
     * Retrieve details of a specific trainer by their ID.
     *
     * This method fetches the trainer's details and returns them.
     *
     * @param int $id The ID of the trainer to be retrieved.
     */
    public function show($id)
    {
        // Attempt to find the trainer by the provided ID
        $trainer = Trainer::find($id);

        // If the trainer is not found, return an error response with a 404 status
        if (!$trainer) {
            return $this->errorResponse('Trainer not found', 404);
        }

        // If the trainer is found, return a successful response with the trainer's details
        return $this->successResponse($trainer, 'Trainer retrieved successfully.');
    }
}
