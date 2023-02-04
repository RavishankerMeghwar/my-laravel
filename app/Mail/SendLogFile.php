<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
class SendLogFile extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
        ->subject("Error log file")
        ->attach(request()->logFilePath)
        ->view("emails.send-log-file");
    }
}
