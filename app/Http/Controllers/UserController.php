<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserConfirmationCode;
use Illuminate\Support\Facades\Auth;

use App\Mail\EmailConfirmation;
use App\Mail\ChangePasswordUrl;
use Carbon\Carbon;
use Mail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function singup(Request $request)
    {

        try {
            $validated = $this->validate($request, [
                'cuil' => 'required',
                'nombre' => 'required',
                'apellido' => 'required',
                'email' => 'required',
                'password' => 'required',
            ]);

            $user = new User();
            $user->cuil = $validated['cuil'];
            $user->nombre = $validated['nombre'];
            $user->apellido = $validated['apellido'];
            $user->email = $validated['email'];

            $user->password = bcrypt($validated['password']);
          //  $user->confirmation_code=$bignum = hexdec( md5("test") );

            $user->save();

            $code = random_int(1000,9999);
            $validation_code = new UserConfirmationCode();
            $validation_code->id = $user->cuil;
            $validation_code->code = $code;
            $validation_code->created_at = Carbon::now()->timestamp;
            $validation_code->save();

            Mail::to('foo@example.com')
            ->cc('bar@example.com')
            ->queue((new EmailConfirmation($user , $code))->from('us@example.com', 'Laravel'));

            return response()->json([
                'status' => true,
                'message' => 'Correo enviado',
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }

    }

    public function validate_new_user(Request $request)
    {
        $validated = $this->validate($request, [
            'cuil' => 'required',
            'confirmation_code' => 'required',
        ]);


        $user = User::where('cuil', $validated['cuil'] )->first();

        $validation_code = UserConfirmationCode::where('id' , $user->cuil )->first();

        if ( $validation_code->code == $validated['confirmation_code'] ){
             
            $user->markEmailAsVerified();
            $user->save();

            //Ademas eliminamos el codigo de confirmacion de la tabla user_confirmation_codes 
            $validation_code->delete();

            return response()->json([
                'status' => true,
                'message' => 'Usuario confirmado',
                'token' => $user->createToken("user_token", ['nivel_1'])->accessToken
            ], 200);

        }else{

            return response()->json([
                'status' => false,
                'message' => 'Codigo de confirmacion erroneo'
            ], 400);

        }
    }

    public function login (Request $request){

        $validated = $this->validate($request, [
            'cuil' => 'required',
            'password' => 'required',
        ]);


        if (Auth::attempt($validated)) {
            $user = Auth::user();
            $token = $user->createToken('user_token', ['nivel_1'])->accessToken;

            //a solo modo informativo se envia que expira en 7 días. Tener en cuenta que la expiración del token se modifica en AuthServiceProvider
            $timestamp = now()->addDays(7);
            $expires_at = date('M d, Y H:i A', strtotime($timestamp));

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_at' => $expires_at
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Credentials',
            ], 400);
        }
    
    }

    public function test (Request $request) {

            return ("llego");
        
    }


    public function password_reset_validation (Request $request){

        $validated = $this->validate($request, [
            'cuil' => 'required',
            
        ]);

        //aca no tengo que usar Auth porque eso funciona con la contraseña y aca no la tengo

            $user = User::where('cuil', $validated['cuil'] )->first();

            $code = random_int(1000,9999);
            
            $validation_code = UserConfirmationCode::where('id', $validated['cuil'] )->first(); 

            if($validation_code){
                $validation_code->code = $code;
                $validation_code->created_at = Carbon::now()->timestamp;
                $validation_code->save();    
                Mail::to('foo@example.com')
                ->cc('bar@example.com')
                ->queue((new ChangePasswordUrl($user , $code))->from('us@example.com', 'Laravel'));
    
                return response()->json([
                    'status' => true,
                    'message' => 'Correo enviado',
                ], 201);
            }else{
            
                $validation_code = new UserConfirmationCode();
                $validation_code->code = $code;
                $validation_code->created_at = Carbon::now()->timestamp;
                $validation_code->save();
                
                Mail::to('foo@example.com')
            ->cc('bar@example.com')
            ->queue((new ChangePasswordUrl($user , $code))->from('us@example.com', 'Laravel'));

            return response()->json([
                'status' => true,
                'message' => 'Correo enviado',
            ], 201);
            }
    }

    public function password_reset (Request $request){

        $validated = $this->validate($request, [
            'cuil' => 'required',
            'new_password' => 'required',
            'verification_code' => 'required',
        ]);

        $validation_code = UserConfirmationCode::where('id' , $validated['cuil']  )->first();

        if ($validation_code == $validated['verification_code'] ){

            $user = User::where('cuil', $validated['cuil'] )->first();
            $user->password = bcrypt($validated['new_password']);
            $user->save();
            $validation_code->delete();

            return response()->json([
                'status' => true,
                'message' => 'Contraseña cambiada',
            ], 201);

        }else{

            return response()->json([
                'status' => false,
                'message' => 'Código de validación erroneo',
            ], 201);
        }


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
