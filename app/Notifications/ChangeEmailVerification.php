<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ChangeEmailVerification extends Notification
{
    use Queueable;
    public $verifyUrl;
    public $to_address;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
        $this->to_address = "new_email";
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
        $name = $notifiable->getFullName();

        return (new MailMessage)
                    ->subject("Verify Email Address")
                    ->greeting('Hello ' . $name . ',')
                    ->line("Click the link below to verify your new email address.")
                    ->action('Verify Email Address', $this->verifyUrl);
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
