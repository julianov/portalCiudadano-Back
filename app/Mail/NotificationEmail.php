<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $body;
    

    public function __construct( $title, $body )
    {
        $this->title = $title;
        $this->body = $body;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('NotificationEmail', ['name' => 'Portal Ciudadano - Provincia de Entre Ríos'])
                    ->withSwiftMessage(function ($message) {
                        $message->setContentType('image/jpeg');
                    });
    }
}