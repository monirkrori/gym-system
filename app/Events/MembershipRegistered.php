<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $membership;

    public function __construct($user, $membership)
    {
        $this->user = $user;
        $this->membership = $membership;

        $membershipName = $membership && isset($membership->name) ? $membership->name : 'غير معروف';

        Notification::create([
            'description' => 'تم تسجيل عضوية جديدة للعضو: ' . $user->name . ' في خطة: ' . $membershipName,
            'user_id' => $user->id,
            'type_name' => 'عضوية جديدة',
            'type_color' => 'green-500',
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
        return 'membership.registered';
    }

    public function broadcastWith()
    {
        $membershipName = $this->membership && isset($this->membership->name) ? $this->membership->name : 'غير معروف';

        return [
            'description' => 'تم تسجيل عضوية جديدة للعضو: ' . $this->user->name . ' في خطة: ' . $membershipName,
            'user' => [
                'name' => $this->user->name,
                'profile_photo' => $this->user->profile->profile_photo ?? null,
            ],
            'type_name' => 'عضوية جديدة',
            'type_color' => 'green-500',
            'status_name' => 'جديد',
            'status_color' => 'blue-500',
            'created_at' => now()->diffForHumans(),
        ];
    }
}
