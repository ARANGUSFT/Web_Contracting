<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeadAssignedNotification extends Notification
{
    use Queueable;

    protected $lead;

    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    public function via($notifiable)
    {
        return ['mail']; // Puedes agregar "database" si quieres guardar en BD
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nuevo Lead Asignado')
                    ->greeting('Hi ' . $notifiable->name)
                    ->line('You have been assigned a new lead: ' . $this->lead->first_name . ' ' . $this->lead->last_name)
                    ->action('See Lead', url('/seller/dashboard'))
                    ->line('¡Good luck with the sale!');
    }
}
