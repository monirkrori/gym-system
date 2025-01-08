<?php

namespace App\Events;

use App\Models\Notification;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TrainingSessionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $trainer;
    public $trainingSession;

    public function __construct(User $trainer, TrainingSession $trainingSession)
    {
        $this->trainer = $trainer;
        $this->trainingSession = $trainingSession;

        Notification::create([
            'description' => 'تمت إضافة جلسة تدريبية جديدة بواسطة المدرب: ' . $trainer->name,
            'user_id' => $trainer->id,
            'type_name' => 'جلسة تدريبية',
            'type_color' => 'purple-500',
            'status_name' => 'جديد',
            'status_color' => 'blue-500',
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('notifications');
    }

    public function broadcastAs()
    {
        return 'training.session.created';
    }

    public function broadcastWith()
    {
        return [
            'description' => 'تمت إضافة جلسة تدريبية جديدة بواسطة المدرب: ' . $this->trainer->name,
            'user' => [
                'name' => $this->trainer->name,
                'profile_photo' => $this->trainer->profile->profile_photo ?? null,
            ],
            'type_name' => 'جلسة تدريبية',
            'type_color' => 'purple-500',
            'status_name' => 'جديد',
            'status_color' => 'blue-500',
            'created_at' => now()->diffForHumans(),
        ];
    }
}
