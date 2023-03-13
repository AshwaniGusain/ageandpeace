<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailChange extends Notification
{
    use Queueable;

    private $user;
    public $address;
    public $oldAddress;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $newAddress)
    {
        $this->user = $user;
        $this->oldAddress = $user->email;
        $this->address = $newAddress;
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
        return (new MailMessage)
                    ->greeting('Hello ' . $this->user->name . ',')
                    ->line('Your account email has been changed.')
                    ->line('The new email address is: ' . $this->address)
                    ->line('If you didn\'t request this change, please contact us using the link below or by phone at (503) 555-1234')
                    //TODO Update this with a real email account or possible a .env value
                    ->action('Notification Action', 'mailto:support@example.com');
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
