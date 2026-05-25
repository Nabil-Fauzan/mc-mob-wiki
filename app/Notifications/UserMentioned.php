<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserMentioned extends Notification
{
    use Queueable;

    public $senderName;
    public $mobName;
    public $mobId;
    public $commentId;

    /**
     * Create a new notification instance.
     */
    public function __construct($senderName, $mobName, $mobId, $commentId)
    {
        $this->senderName = $senderName;
        $this->mobName = $mobName;
        $this->mobId = $mobId;
        $this->commentId = $commentId;
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
            'sender' => $this->senderName,
            'mob_name' => $this->mobName,
            'url' => route('mobs.show', $this->mobId) . '#comment-' . $this->commentId,
            'message' => 'mentioned you in a field note about ' . $this->mobName,
        ];
    }
}
