<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $baseUrl;
    public $verifyUrl;
    public $app_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId, $passwordHash)
    {
        $this->app_name = env("APP_NAME");
        $this->baseUrl = env('YOUR_SERVER_URL');
        $this->verifyUrl = '/#/verify?id='.$userId.'&hash='.$passwordHash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('verify');
    }
}
