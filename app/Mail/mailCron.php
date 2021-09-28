<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class mailCron extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $quotes;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $quotes)
    {
        $this->date = $date;
        $this->quotes = $quotes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.cron-mail');
    }
}
