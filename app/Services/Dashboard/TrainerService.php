<?php

namespace App\Services\Dashboard;

use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;
use App\Services\FilterService;

class TrainerService
{
    protected FilterService $filterService;

    public function __construct(FilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    /**
     * Create a new trainer and manage roles/memberships.
     */
    public function createTrainer(array $data)
    {
        $user = User::find($data['user_id']);
        $membership = UserMembership::where('user_id', $user->id)->first();
        $trainer = Trainer::where('user_id', $user->id)->first();

        if ($membership) {
            $membership->delete();
            $user->removeRole('member');
            $user->assignRole('trainer');
        } elseif ($trainer) {
            throw new \Exception('Trainer already exists in the gym.');
        }

        return Trainer::create($data);
    }

    /**
     * Update a trainer's details.
     */
    public function updateTrainer(Trainer $trainer, array $data)
    {
        $trainer->update($data);
        return $trainer;
    }

    /**
     * Get trainers with filters and pagination.
     */
    public function getTrainersWithFilters(array $filters)
    {
        $query = Trainer::query();

        // Apply filters using FilterService
        $this->filterService->applyFilters($query, $filters);

        // Paginate the result
        $trainers = $query->paginate(10);

        return [
            'trainers' => $trainers,
            'totalTrainers' => Trainer::count(),
            'activeTrainer' => Trainer::where('status', 'available')->count(),

        ];
    }

    /**
     * Delete a trainer.
     */
    public function deleteTrainer(Trainer $trainer)
    {
        return $trainer->delete();
    }

    /**
     * Get all users for trainer selection.
     */
    public function getAllUsers()
    {
        return User::all();
    }
}
