<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrainingCompletedNotification extends Notification
{
    use Queueable;

    protected $enrollee;

    /**
     * Create a new notification instance.
     */
    public function __construct($enrollee)
    {
        $this->enrollee = $enrollee;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Congratulations! You have successfully completed the training program.')
            ->line('Training Program: ' . $this->enrollee->program->title)
            ->action('View Program', url('/programs/' . $this->enrollee->program->id))
            ->line('Thank you for participating!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'program_title' => $this->enrollee->program->title,
            'completed_at' => now(),
        ];
    }
}
