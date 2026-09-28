<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // Notification cũng phải chạy qua queue "reminders" giống job gọi nó
        $this->onQueue('reminders');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nhắc nhở từ hệ thống')
            ->greeting("Xin chào {$notifiable->name},")
            ->line('Đây là email nhắc nhở tự động được gửi định kỳ.')
            ->action('Truy cập hệ thống', url('/'))
            ->line('Cảm ơn bạn đã sử dụng ứng dụng của chúng tôi!');
    }
}