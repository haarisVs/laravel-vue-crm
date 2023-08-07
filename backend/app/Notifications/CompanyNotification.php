<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use App\Models\Company;

class CompanyNotification extends Notification implements ShouldQueue
{
    use Queueable;


    protected $companyName;
    protected $companyId;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($companyName, $companyId)
    {
        $this->companyName = $companyName;
        $this->companyId = $companyId;
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
            ->subject('Welcome onboard!, Your Company has been registered')
            ->greeting('Hello!')
            ->line('We are excited to inform you that a new company has been registered with our application.')
            ->line('Company Name: ' . ($this->companyName ?? ''))
            ->action('View Company', url('/companies/' . $this->companyId))
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
