<?php
namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;

        // تخزين الإشعار في قاعدة البيانات
        Notification::create([
            'description' => 'تم إنشاء حساب جديد: ' . $user->name,
            'user_id' => $user->id,
            'type_name' => 'إنشاء حساب',
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
        return 'user.registered';
    }

    public function broadcastWith()
    {
        return [
            'description' => 'تم إنشاء حساب جديد: ' . $this->user->name,
            'user' => [
                'name' => $this->user->name,
                'profile_photo' => $this->user->profile->profile_photo ?? null,
            ],
            'type_name' => 'إنشاء حساب',
            'type_color' => 'green-500',
            'status_name' => 'جديد',
            'status_color' => 'blue-500',
            'created_at' => now()->diffForHumans(),
        ];
    }
}
