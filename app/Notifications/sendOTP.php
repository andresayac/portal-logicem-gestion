<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendOTP extends Notification
{
    use Queueable;

    protected $data_mail;

    /**
     * Create a new notification instance.
     */
    public function __construct($data_mail)
    {
        $this->data_mail = $data_mail;
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
        $messageLines = explode("\n", $this->data_mail['message']);
        $mailMessage = (new MailMessage)
            ->subject($this->data_mail['title'])
            ->greeting('Hola ' . $this->data_mail['name']);
        foreach ($messageLines as $line) {
            $mailMessage->line($line);
        }

        $mailMessage->line('Gracias por usar nuestra aplicación!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
