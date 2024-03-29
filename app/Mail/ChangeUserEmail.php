<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class ChangeUserEmail extends Mailable
{
	use Queueable, SerializesModels;

	public $user;
	public $hash;
	public $cuil;
	public $mixto;
    public $new_email; 

	public function __construct(User $user, $new_email ,$hash)
	{
		$this->user = $user;
		try{
			//$this->hash = Crypt::encrypt($hash);
			//$this->cuil = Crypt::encrypt($user->cuil);
			$this->mixto = Crypt::encrypt($user->cuil.'/'.$new_email. '/'.$hash);
		} catch (DecryptException $e){
			return redirect()->back()->withErrors(['error' => $e->getMessage()]);
		}
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build()
	{
		return $this->view('changeNewEmail', ['name' => 'Portal Ciudadano - Provincia de Entre Ríos'])
		->withSwiftMessage(function ($message) {
			$message->setContentType('image/jpeg');
		});
	}
}
