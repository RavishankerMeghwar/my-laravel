<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreatePumpNotif extends Mailable
{
    use Queueable, SerializesModels;
    public $pump, $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pump, $user)
    {
        $this->pump = $pump;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('credentials.admin_email_address'))
        ->subject($this->user->first_name." ".$this->user->last_name ." created a new pump. Please check it.")
        ->view("emails.pump-create-notif");
    }
}
