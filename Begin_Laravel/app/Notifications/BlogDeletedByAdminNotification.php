<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BlogDeletedByAdminNotification extends Notification
{
    public function __construct(
        public string $blogName,
        public string $reason,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bài viết của bạn đã bị quản trị viên xóa')
            ->greeting("Xin chào {$notifiable->name},")
            ->line("Bài viết \"{$this->blogName}\" đã bị xóa.")
            ->line("Lý do: {$this->reason}")
            ->line('Nếu có thắc mắc, vui lòng liên hệ quản trị viên.');
    }
}
