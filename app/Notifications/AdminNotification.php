<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
   public function via($notifiable){
       return ['database'];
   }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'عضو جديد انضم للنادي!',
            'body' => 'قام العضو محمد بالاشتراك في الباقة الذهبية.',
            'url' => url('/admin/members')
        ];
    }

}
