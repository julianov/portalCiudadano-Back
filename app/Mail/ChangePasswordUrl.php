<?php

namespace App\Mail;

<<<<<<< HEAD
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
    public $cuil;

    public function __construct(User $user, $hash)
    {
        $this->user = $user;
        $this->hash = $hash;
        $this->cuil = $user->cuil;

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
=======
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ChangePasswordUrl extends Mailable
{
	use Queueable, SerializesModels;

	public $user;
	public $hash;
	public $cuil;

	public function __construct(User $user, $hash)
	{
		$this->user = $user;
		$this->hash = base64_encode($hash);
		$this->cuil = base64_encode($user->cuil);

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
>>>>>>> e40bfe757f261588605a6116f2891d17defade28
}
