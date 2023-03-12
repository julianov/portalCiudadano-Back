<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthGetTokenAutenticarRequest;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;

use \Firebase\JWT\JWT;
use Illuminate\Support\Str;
use App\Http\Services\UserService;


class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */

    protected UserService $userService;

    public function __construct(UserService $userService)
	{

		$this->userService = $userService;

	}

    public function getValidationAfip(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                "cuil" => "required",
                "code" => "required|string",
            ]);
            
            $cuil = $request->cuil;
            $code = $request->code;

            $client = new \GuzzleHttp\Client();

            $url = config("autenticar.base_url_api")."protocol/openid-connect/token";

            $redirectUri = config("autenticar.redirect_uri");


            $response = $client->post($url, [
                RequestOptions::FORM_PARAMS => [
                    "grant_type" => config("autenticar.grant_type"),
                    "code" => $code,
                    "redirect_uri" => $redirectUri,
                    "client_id" => config("autenticar.client_id"),
                    "client_secret" => config("autenticar.secret"),
                ],
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            $access_token = $data['access_token'];
            $decoded_token = JWT::decode($access_token, $secret_key, array('HS256'));
            
            $cuit = $decoded_token->cuit;
            $tipo_persona = $decoded_token->tipo_persona;
            $proveedor = $decoded_token->proveedor;
            $preferred_username = $decoded_token->preferred_username;
            $given_name = $decoded_token->given_name;
            $family_name = $decoded_token->family_name;
            $nivel = $decoded_token->nivel;
            
            $user = $this->userService->getUser($cuil);

            //return response()->json($data, 200);

            if($user){

                $user_cuil = $user->cuil;
                $user_name = $user->name;
                $user_last_name = $user->last_name;

                $normalizedName1 = mb_strtolower(Str::slug($user_name, '-', 'es', true));
                $normalizedName2 = mb_strtolower(Str::slug($given_name, '-', 'es', true));
                $normalizedLast_name1 = mb_strtolower(Str::slug($user_last_name, '-', 'es', true));
                $normalizedLast_name2 = mb_strtolower(Str::slug($family_name, '-', 'es', true));

                if ($user_cuil == $cuit && $normalizedName1 == $normalizedName2 && $normalizedLast_name1 = $normalizedLast_name2 ){
                    //datos consistentes sube a nivel 3
                    
                    $res_user_auth = $this->userService->setAuthType($user, "AFIP", "level_3");

					if ($res_user_auth) {

						return response()->json([
							'status' => true,
							'message' => 'Application Validated User Identity',
							'token' => $user->createToken("user_token", ['level_3'])->accessToken
						]);

					} else {

						return response()->json([
							'status' => false,
							'message' => 'Internal server problem, please try again later'
						], 503);

					}

                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Inconsistency in data by application'
                    ], 409);
                    
                }

            }else{
                return response()->json([
					'status' => false,
					'message' => 'User not found'
				], 404);
            }



        } catch (\Exception $e) {

            if ($e instanceof BadResponseException) {
                $response = $e->getResponse();
                $responseBodyAsString = $response->getBody()->getContents();
                return response()->json(json_decode($responseBodyAsString), $e->getCode());
            }

            return response()->json([
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function getUrlAfip(Request $request)

    {
        $request->validate([
        // "cuil" => "required|min:11|max:11",
        "cuil" => "required|string",
        ]);

        return "https://tst.autenticar.gob.ar/auth/realms/appentrerios-afip/protocol/openid-connect/auth?response_type=code&client_id=appentrerios&redirect_uri=https://jaodevvps.online:8443/api/v0/getTokenAfip/".$request['cuil']."&scope=openid";
   
    }

/*    public function getAfipToken(Request $request){
   $code = $request->input('code');
            $client = new \GuzzleHttp\Client();
            $url = config("autenticar.base_url_api")."protocol/openid-connect/token";
            $redirectUri = config("autenticar.redirect_uri")."/"."27049902072";
        $response = $client->post($url, [
            RequestOptions::FORM_PARAMS => [
                "grant_type" => config("autenticar.grant_type"),
                "code" => $code,
                "redirect_uri" => $redirectUri,
                "client_id" => config("autenticar.client_id"),
                "client_secret" => config("autenticar.secret"),
            ],
            "headers" => [
                "Content-Type" => "application/x-www-form-urlencoded",
            ],
        ]);
        Mail::to("julianov403@gmail.com")
				->queue((new PruebaEmail($response))
					->from('ciudadanodigital@entrerios.gov.ar', 'Ciudadano Digital - Provincia de Entre Ríos')
					->subject('Prueba de token'));
    }*/

}
