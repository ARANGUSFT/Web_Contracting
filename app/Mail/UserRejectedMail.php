<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $reason;

    public function __construct($user, $reason = null)
    {
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Your account application was not approved')
                    ->view('emails.user-rejected');
    }
}