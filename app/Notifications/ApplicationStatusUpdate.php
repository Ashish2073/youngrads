<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ApplicationStatusUpdate extends Notification
{
    use Queueable;

    public $application;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $status = config('setting.application.status.' . $this->application->status);
        $content = config("setting.email.application.status.{$this->application->status}.content");

        $program_name = $this->application->campusProgram->program->name;
        return (new MailMessage)
                    ->subject('Application Status Update - ' . $this->application->application_number)
                    ->greeting("Hi {$notifiable->getFullName()},")
                    ->line("<strong>Status Update:</strong> {$status} ")
                    ->line("<strong>Application ID:</strong> {$this->application->application_number}")
                    ->line("<strong>Program:</strong> {$program_name}")
                    ->line($content)
                    ->line(config('setting.email.application.footer'))
                    ->line(config('setting.email.application.footer_2'))
                    ->salutation("Thanks<br>" . config('setting.email.application.contact.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
