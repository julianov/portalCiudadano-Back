<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
class PruebaEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $json;
	public $hash;
	public $cuil;
	public $mixto;

	public function __construct(User $json)
	{
		$this->json = $json;

	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{	
		return $this->view('prueba', ['name' => 'Portal Ciudadano - Provincia de Entre Ríos']);
	}
}