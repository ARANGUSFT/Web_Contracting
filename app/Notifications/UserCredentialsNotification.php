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
        $appName = "Contracting Alliance Inc";
        
        return (new MailMessage)
            ->subject("Your Account Credentials - {$appName}")
            ->view('emails.user-credentials', [
                'name' => $notifiable->name,
                'email' => $this->email,
                'password' => $this->password,
                'loginUrl' => $this->loginUrl,
                'appName' => $appName
            ]);
    }
}