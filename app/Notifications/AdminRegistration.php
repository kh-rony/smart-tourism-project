<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    protected $admin;
    protected $role;
    protected $route;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($admin, $role, $route)
    {
        //
        $this->admin = $admin;
        $this->role = $role;
        $this->route = $route;
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
                    ->subject($this->role. ' Registration')
                    ->greeting('Hello! ' . $this->admin->name)
                    ->line("You are added as a {$this->role}.")
                    ->action('Complete Registration', url($this->route))
                    ->line('Thank you for using our application!');
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
