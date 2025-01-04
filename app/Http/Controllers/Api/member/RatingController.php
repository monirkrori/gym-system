<?php
namespace App\Http\Controllers\Api\member;

use App\Models\Rating;
use App\Http\Controllers\Controller;
use App\Http\Requests\member\ReplyRatingRequest;
use App\Http\Requests\member\StoreRatingRequest;
use App\Http\Requests\member\UpdateRatingRequest;

class RatingController extends Controller
{

    public function store(StoreRatingRequest $request)
    {
        // Validate the incoming data using the form request
        $validated = $request->validated();

        // Set user_id from authenticated user
        $validated['user_id'] = auth()->id();

        // Create the rating
        $rating = Rating::create($validated);

        // Return success response
        return $this->successResponse($rating, 'Rating successfully added.');
    }

    public function update(UpdateRatingRequest $request, $id)
    {
        // Find the rating by ID
        $rating = Rating::find($id);

        // Ensure the rating belongs to the current user
        if ($rating->user_id != auth()->id()) {
            return $this->errorResponse('Unauthorized', 403);
        }

        // Validate the updated data
        $validated = $request->validated();

        // Update the rating
        $rating->update($validated);

        // Return success response
        return $this->successResponse($rating, 'Rating successfully updated.');
    }
//--------------------------------------------------------------------------------//

    public function show($rateableId)
    {
        // Get all active ratings for the specified rateable entity, with their replies
        $ratings = Rating::where('rateable_id', $rateableId)
            ->where('status', 'active') // Only active ratings
            ->with(['user', 'replies']) // Include user who gave the rating and the replies
            ->get();

        // Return the ratings with their replies
        return $this->successResponse($ratings, 'Ratings and replies fetched successfully.');
    }

//--------------------------------------------------------------------------------//

    public function reply(ReplyRatingRequest $request, $ratingId)
    {
        // Validate the reply data
        $validated = $request->validated();

        // Set user_id and parent_id for the reply
        $validated['user_id'] = auth()->id();
        $validated['parent_id'] = $ratingId;

        // Get the rateable_id and rateable_type from the parent rating
        $parentRating = Rating::findOrFail($ratingId);
        $validated['rateable_id'] = $parentRating->rateable_id;
        $validated['rateable_type'] = $parentRating->rateable_type;

        // Create the reply rating
        $reply = Rating::create($validated);

        // Return success response
        return $this->successResponse($reply, 'Reply successfully added.');
    }
}
