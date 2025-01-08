<?php
namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipPackageRegistered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $membershipPackage;

    public function __construct($user, $membershipPackage)
    {
        $this->user = $user;
        $this->membershipPackage = $membershipPackage;

        $packageName = $membershipPackage ? $membershipPackage->name : 'غير معروف';

        Notification::create([
            'description' => 'تم تسجيل عضو في باقة: ' . $packageName . ' - ' . $user->name,
            'user_id' => $user->id,
            'type_name' => 'تسجيل عضو في باقة جديدة',
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
        return 'membershipPackage.registered';
    }

    public function broadcastWith()
    {
        $packageName = $this->membershipPackage ? $this->membershipPackage->name : 'غير معروف';

        return [
            'description' => 'تم تسجيل عضو في باقة: ' . $packageName . ' - ' . $this->user->name,
            'user' => [
                'name' => $this->user->name,
                'profile_photo' => $this->user->profile->profile_photo ?? null,
            ],
            'type_name' => 'تسجيل عضو في باقة جديدة',
            'type_color' => 'green-500',
            'status_name' => 'جديد',
            'status_color' => 'blue-500',
            'created_at' => now()->diffForHumans(),
        ];
    }
}
