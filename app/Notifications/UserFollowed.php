<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    use Queueable;

    public $followerName;
    public $followerSlug;

    /**
     * Create a new notification instance.
     */
    public function __construct($followerName, $followerSlug)
    {
        $this->followerName = $followerName;
        $this->followerSlug = $followerSlug;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sender' => $this->followerName,
            'url' => route('researchers.show', $this->followerSlug),
            'message' => 'established a connection with you.',
        ];
    }
}
