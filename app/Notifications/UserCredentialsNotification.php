<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserCredentialsNotification extends Notification
{
    protected $email;
    protected $password;
    protected $loginUrl;

    public function __construct($email, $password, $loginUrl)
    {
        $this->email = $email;
        $this->password = $password;
        $this->loginUrl = $loginUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tus credenciales de acceso')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha creado una cuenta para ti. A continuación, te proporcionamos tus credenciales de acceso:')
            ->line('**Correo:** ' . $this->email)
            ->line('**Contraseña:** ' . $this->password)
            ->action('Iniciar sesión', $this->loginUrl)
            ->line('Por favor, cambia tu contraseña después de iniciar sesión.');
    }
}
