<?php

namespace App\Services;

namespace App\Services;

use App\Models\Trainer;
use App\Models\User;
use App\Models\UserMembership;

class TrainerService
{
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
}
