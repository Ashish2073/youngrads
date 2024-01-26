<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class CustomEmailVerification extends Notification
{
    use Queueable;
    public $verifyUrl;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($verifyUrl)
    {
        $this->verifyUrl = $verifyUrl;
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
                    ->greeting('Hello ' . $name . ' and thank you for registering!')
                    ->line("You're only a step away from increasing your tip income - click the link below to verify your email address and to fill out your profile.")
                    ->line("Remember - you're more likely to generate greater tips with a compelling profile. Great profiles tell a story that encourages the person leaving you a tip to give more. Be impactful and tell tip givers about your hopes, dreams, aspirations, and goals. Tip givers will often give more tips when they know more about where their hard-earned tip is going.")
                    ->action('Verify Email Address', $this->verifyUrl)
                    ->line('If you did not create an account, no further action is required.');
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
