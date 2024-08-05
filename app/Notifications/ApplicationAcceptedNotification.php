<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationAcceptedNotification extends Notification
{
    use Queueable;

    protected $trainingProgram;

    /**
     * Create a new notification instance.
     */
    public function __construct($trainingProgram)
    {
        $this->trainingProgram = $trainingProgram;
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
            ->subject('Application Accepted')
            ->line('Congratulations! Your application for the training program has been accepted.')
            ->action('View Program', url('/training-programs/' . $this->trainingProgram->id))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'program_title' => $this->trainingProgram->title,
            'url' => url('/training-program/' . $this->trainingProgram->id),
            'agency_name' => $this->trainingProgram->agency->userInfo->name,
        ];
    }
}
