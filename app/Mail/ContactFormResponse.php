<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactFormResponse extends Mailable
{
    use Queueable, SerializesModels;

    public $info = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $info)
    {
        $this->info = $info;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this
                ->from(config('site.mail_from'))
                ->view('emails.contact-form') // $info will be automatically passed in a Mailable because it is a public property.
        ;
    }
}
