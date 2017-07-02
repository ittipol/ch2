<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountRecovery extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $key;

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
      $subject = 'รีเซ็ตรหัสผ่าน';

      $data = array(
        'key' => $this->key,
        'email' => $this->email,
        'link' => url('user/recover').'?user='.$this->email.'&key='.$this->key
      );

      return $this->view('emails.account_recovery', $data)
      ->from($address, $name)
      ->subject($subject);
    }
}
