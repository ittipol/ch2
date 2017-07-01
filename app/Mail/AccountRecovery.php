<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountRecovery extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'sundaysquare.help@gmail.com';
        $name = 'Sunday Square Support';
        $subject = 'Account Recovery';

        return $this->view('emails.account_recovery')
        ->with('total',111)
        ->from($address, $name)
        ->subject($subject);
    }
}
