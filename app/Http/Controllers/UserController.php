<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use App\Mail\EmailConfirmation;

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
            $user->cuil = $request->cuil;
            $user->nombre = $request->nombre;
            $user->apellido = $request->apellido;
            $user->email = $request->email;

            $user->password = bcrypt($request->password);
            $user->confirmation_code=random_int(1000,9999);

            $user->save();
   
            Mail::to('foo@example.com')
            ->cc('bar@example.com')
            ->queue((new EmailConfirmation($user))->from('us@example.com', 'Laravel'));

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

        if ($user['confirmation_code']==$validated['confirmation_code']){
             
            $user->markEmailAsVerified();
            $user->save();
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
            $token = $user->createToken('user_token',['nivel_1'])->accessToken;
            
            $minutes = 1440;
            $timestamp = now()->addMinute($minutes);
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
