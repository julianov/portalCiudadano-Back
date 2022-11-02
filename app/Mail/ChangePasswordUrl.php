<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;


class ChangePasswordUrl extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $hash;

    public function __construct(User $user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('changePassword', ['name' => 'Portal Ciudadano - Provincia de Entre Ríos']);
    }
}
