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
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;


class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */

    protected UserService $userService;
	private EntreRiosWSService $wsService;

    public function __construct(UserService $userService, EntreRiosWSService $wsService)
	{

		$this->userService = $userService;
        $this->wsService = $wsService;

	}

    public function getUrlAfip(Request $request)

    {
        $request->validate([
        // "cuil" => "required|min:11|max:11",
        "cuil" => "required|string",
        ]);

        return "https://tst.autenticar.gob.ar/auth/realms/appentrerios-afip/protocol/openid-connect/auth?response_type=code&client_id=appentrerios&redirect_uri=https://sistemasdesa.entrerios.gov.ar/cdig/node/AutenticarToken".$request['cuil']."&scope=openid";
   
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



    public function validateFaceToFaceGetData(Request $request)
    {
        
        $request->validate([
            "cuil_actor" => "required",
            "cuil_citizen" => "required|string",
            "token" => "required|string",
        ]);

        $dni = $this->userService->getDniFromCuil($request['cuil_actor']);
		$rs = $this->wsService->checkUserCuil($dni);
        if ($rs->getData()->status === true) {
            if($rs->getData()->Actor=== true){

                $host = env('BASEUR_ER_WS_TOKEN');

                $data = [
                    '_tk' => $request['token']
                ];

                $response = $client->request('POST', $host, [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $data
                ]);

                if($response == 0) {

                    $user = $this->userService->getUser($request['cuil_citizen']);

                    if ($user){
    
                        $column_name = "USER_ID";
                        $column_value = $user->id;
                        $table = "USER_CONTACT";
                        $user_data = $this->userService->getRow($table, $column_name, $column_value);
    
                        $user_data = [
                            "user" => $user,
                            "user_contact" => $user_data
                        ];
    
                        return response()->json([
                            'status' => true,
                            'message' => 'User found',
                            'user_data' => $user_data
                        ]);
    
                    }else{
    
                        return response()->json([
                            'status' => false,
                            'message' => 'User not found'
                        ], 404);
    
                    }

                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Bad token'
                    ], 401);

                }

            }else{

                return response()->json([
					'status' => false,
					'message' => 'No actor request'
				], 404);

            }
        }

    }



    public function validateFaceToFaceCitizen(Request $request)
    {

        $request->validate([
            "cuil_actor" => "required",
            "cuil_citizen" => "required|string",
            "token" => "required|string",

        ]);

        $dni = $this->userService->getDniFromCuil($request['cuil_actor']);
		$rs = $this->wsService->checkUserCuil($dni);
        if ($rs->getData()->status === true) {
            if($rs->getData()->Actor=== true){

                $host = env('BASEUR_ER_WS_TOKEN');

                $data = [
                    '_tk' => $request['token']
                ];

                $response = $client->request('POST', $host, [
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $data
                ]);

                if($response == 0) {

                    $user = $this->userService->getUser($request['cuil_citizen']);

                if ($user){

                    $res_user_auth = $this->userService->setAuthType($user, "PRESENCIAL", "level_3");

                    if ($res_user_auth) {

						return response()->json([
							'status' => true,
							'message' => 'Presential Validated User Identity',
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
                        'message' => 'User not found'
                    ], 404);

                }

                }else{

                    return response()->json([
                        'status' => false,
                        'message' => 'Bad token'
                    ], 401);

                }

            }else{

                return response()->json([
					'status' => false,
					'message' => 'No actor request'
				], 404);

            }
        }
        
    }


}
