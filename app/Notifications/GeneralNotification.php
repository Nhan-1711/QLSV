<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $senderName;
    public $senderRole;
    public $attachmentPath;
    public $originalFileName;
    public $senderId;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $message, $senderName, $senderRole, $attachmentPath = null, $originalFileName = null, $senderId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->senderName = $senderName;
        $this->senderRole = $senderRole;
        $this->attachmentPath = $attachmentPath;
        $this->originalFileName = $originalFileName;
        $this->senderId = $senderId;
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
            'title' => $this->title,
            'message' => $this->message,
            'sender_id' => $this->senderId,
            'sender_name' => $this->senderName,
            'sender_role' => $this->senderRole,
            'attachment_url' => $this->attachmentPath ? asset('storage/' . $this->attachmentPath) : null,
            'original_attachment_name' => $this->originalFileName ?? ($this->attachmentPath ? basename($this->attachmentPath) : null),
        ];
    }
}
