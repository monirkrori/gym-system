<?php

namespace App\Http\Controllers\Api\member;

use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreRatingRequest;
use App\Http\Requests\Member\UpdateRatingRequest;
use App\Http\Requests\Member\ReplyRatingRequest;
use App\Models\Rating;
use App\Services\Member\RatingService;
use Illuminate\Http\JsonResponse;

class RatingController extends Controller
{
    protected $ratingService;

    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Store a new rating.
     *
     * @param StoreRatingRequest $request
     * @return JsonResponse
     */
    public function store(StoreRatingRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = auth()->id();

            $rating = $this->ratingService->createRating($data);

            return $this->successResponse($rating, 'Rating successfully added.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update an existing rating.
     *
     * @param UpdateRatingRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRatingRequest $request, $id)
    {
        try {
            $rating = Rating::findOrFail($id);

            if ($rating->user_id !== auth()->id()) {
                return $this->errorResponse('Unauthorized', 403);
            }

            $rating->update($request->validated());

            // Update the average rating for the rateable entity
            $this->ratingService->updateRateableRatingAvg($rating->rateable_type, $rating->rateable_id);

            return $this->successResponse($rating, 'Rating successfully updated.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Show ratings for a specific rateable entity.
     *
     * @param int $rateableId
     * @param string $rateableType
     * @return JsonResponse
     */
    public function show( $rateableId,  $rateableType)
    {
        $ratings = Rating::where('rateable_id', $rateableId)
            ->where('rateable_type', $rateableType)
            ->where('status', 'active')
            ->with(['user', 'replies'])
            ->get();

        return $this->successResponse($ratings, 'Ratings and replies fetched successfully.');
    }

    /**
     * Add a reply to a rating.
     *
     * @param ReplyRatingRequest $request
     * @param int $ratingId
     * @return JsonResponse
     */
    public function reply(ReplyRatingRequest $request,  $ratingId)
    {
        try {
            $parentRating = Rating::findOrFail($ratingId);

            $data = $request->validated();
            $data['user_id'] = auth()->id();
            $data['parent_id'] = $ratingId;
            $data['rateable_id'] = $parentRating->rateable_id;
            $data['rateable_type'] = $parentRating->rateable_type;

            $reply = Rating::create($data);

            return $this->successResponse($reply, 'Reply successfully added.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }


}
