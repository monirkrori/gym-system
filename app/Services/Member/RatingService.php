<?php

namespace App\Services\Member;

use App\Models\Rating;
use App\Models\Trainer;
use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RatingService
{
    /**
     * Create a new rating.
     *
     * @param array $data
     * @return Rating
     * @throws \Exception
     */
    public function createRating(array $data)
    {
        // Check if the user has already rated this entity
        $existingRating = Rating::where('user_id', $data['user_id'])
            ->where('rateable_id', $data['rateable_id'])
            ->where('rateable_type', $data['rateable_type'])
            ->exists();

        if ($existingRating) {
            throw new \Exception('You have already rated this entity.');
        }

        // Create the rating
        $rating = Rating::create($data);

        // Update the average rating for the rateable entity
        $this->updateRateableRatingAvg($data['rateable_type'], $data['rateable_id']);

        return $rating;
    }

    /**
     * Update the average rating for a rateable entity.
     *
     * @param string $rateableType
     * @param int $rateableId
     * @return void
     */
    public function updateRateableRatingAvg($rateableType, $rateableId)
    {
        if ($rateableType === 'trainer') {
            $this->updateTrainerRatingAvg($rateableId);
        } elseif ($rateableType === 'session') {
            $this->updateSessionRatingAvg($rateableId);
        }
    }

    /**
     * Update the average rating for a trainer.
     *
     * @param int $trainerId
     * @return void
     */
    public function updateTrainerRatingAvg( $trainerId)
    {
        $trainer = Trainer::findOrFail($trainerId);

        $averageRating = Rating::where('rateable_id', $trainerId)
            ->where('rateable_type', 'trainer')
            ->avg('rating');

        $trainer->rating_avg = $averageRating;
        $trainer->save();
    }

    /**
     * Update the average rating for a training session.
     *
     * @param int $sessionId
     * @return void
     */
    public function updateSessionRatingAvg( $sessionId)
    {
        $session = TrainingSession::findOrFail($sessionId);

        $averageRating = Rating::where('rateable_id', $sessionId)
            ->where('rateable_type', 'session')
            ->avg('rating');

        $session->rating_avg = $averageRating;
        $session->save();
    }
}
