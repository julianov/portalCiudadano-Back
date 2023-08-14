<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthGetTokenAutenticarRequest;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use App\Http\Services\ErrorService;
use \Firebase\JWT\JWT;

use Illuminate\Support\Str;
use App\Http\Services\UserService;
use App\Services\WebServices\WsEntreRios\EntreRiosWSService;

use Illuminate\Support\Facades\Http;

use App\Repositories\PLSQL\GenericRepository;


use App\Http\Requests\Auth\validateFaceToFaceCitizenRequest;
use App\Http\Requests\Auth\ActorRedirectRequest;
use App\Models\User;


class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */

    protected UserService $userService;
	private EntreRiosWSService $wsService;
	protected GenericRepository $genericRepository;
    protected ErrorService $errorService;

    public function __construct(UserService $userService, EntreRiosWSService $wsService, GenericRepository $genericRepository, ErrorService $errorService)
	{

		$this->userService = $userService;
        $this->wsService = $wsService;
        $this->genericRepository = $genericRepository;
        $this->errorService = $errorService;

	}

    public function getUrlAfip(Request $request)
    {
        $request->validate([
        // "cuil" => "required|min:11|max:11",
        'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
    ]);
        $redirectUri = config("autenticar.redirect_uri_afip");

        return "https://tst.autenticar.gob.ar/auth/realms/appentrerios-afip/protocol/openid-connect/auth?response_type=code&client_id=appentrerios&redirect_uri=".$redirectUri."&scope=openid";
   
    }

    public function getUrlMiArgentina(Request $request)
    {
        $request->validate([
        // "cuil" => "required|min:11|max:11",
        'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
        ]);
        $redirectUri = config("autenticar.redirect_uri_miargentina");

        return "https://hml.autenticar.gob.ar/auth/realms/appentrerios-miarg/protocol/openid-connect/auth?response_type=code&client_id=appentrerios&redirect_uri=".$redirectUri."&scope=openid";
                
    }

    public function getValidationAfip(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
                "code" => "required|string",
            ]);
            
            $cuil = $request->cuil;
            $code = $request->code;

            $client = new \GuzzleHttp\Client();

            $url = config("autenticar.base_url_api_afip")."protocol/openid-connect/token";

            $redirectUri = config("autenticar.redirect_uri_afip");

            $response = $client->post($url, [
                RequestOptions::FORM_PARAMS => [
                    "grant_type" => config("autenticar.grant_type"),
                    "code" => $code,
                    "redirect_uri" => $redirectUri,
                    "client_id" => config("autenticar.client_id"),
                    "client_secret" => config("autenticar.secret_afip"),
                ],
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            $access_token = $data['access_token'];

/*            $decoded_token = base64_decode($access_token);*/

            list($header, $payload, $signature) = explode('.', $access_token);
            $jsonToken = base64_decode($payload);
            $decoded_token = json_decode($jsonToken, true);
                        
            $cuit = $decoded_token['cuit'];
            $tipo_persona = $decoded_token['tipo_persona'];
            $proveedor = $decoded_token['proveedor'];
            $preferred_username = $decoded_token['preferred_username'];
            $given_name = $decoded_token['given_name'];
            $family_name = $decoded_token['family_name'];
            $nivel = $decoded_token['nivel'];
            
            $user = $this->userService->getUser($cuil);

            if($user){

                $user_cuil = $user->cuil;
                $user_name = $user->name;
                $user_last_name = $user->last_name;

                $normalizedName1 = mb_strtolower(strtolower($user_name));
                $normalizedName2 = mb_strtolower(strtolower($given_name));
                $normalizedLast_name1 = mb_strtolower(strtolower($user_last_name));
                $normalizedLast_name2 = mb_strtolower(strtolower($family_name));

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

						return $this->errorService->databaseWriteError();

					}

                }else{

                    return $this->errorService->dataInconsistency();
                    
                }

            }else{
                return $this->errorService->badUser();
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



    public function getValidationMiArgentina(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'cuil' => 'required|numeric|regex:/^[0-9]{11}$/',
                "code" => "required|string",
            ]);
            
            $cuil = $request->cuil;
            $code = $request->code;

            $client = new \GuzzleHttp\Client();

            $url = config("autenticar.base_url_api_miargentina")."protocol/openid-connect/token";

            $redirectUri = config("autenticar.redirect_uri_miargentina");

            $response = $client->post($url, [
                RequestOptions::FORM_PARAMS => [
                    "grant_type" => config("autenticar.grant_type"),
                    "code" => $code,
                    "redirect_uri" => $redirectUri,
                    "client_id" => config("autenticar.client_id"),
                    "client_secret" => config("autenticar.secret_miargentina"),
                ],
                "headers" => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
            ]);


            $data = json_decode($response->getBody()->getContents(), true);

            $access_token = $data['access_token'];

            list($header, $payload, $signature) = explode('.', $access_token);
            $jsonToken = base64_decode($payload);
            $decoded_token = json_decode($jsonToken, true);
                        
            $cuit = $decoded_token['cuit'];
            $tipo_persona = $decoded_token['tipo_persona'];
            $proveedor = $decoded_token['proveedor'];
            $preferred_username = $decoded_token['preferred_username'];
            $given_name = $decoded_token['given_name'];
            $family_name = $decoded_token['family_name'];
            
            $user = $this->userService->getUser($cuil);

            if($user){

                $user_cuil = $user->cuil;
                $user_name = $user->name;
                $user_last_name = $user->last_name;

                $normalizedName1 = mb_strtolower(strtolower($user_name));
                $normalizedName2 = mb_strtolower(strtolower($given_name));
                $normalizedLast_name1 = mb_strtolower(strtolower($user_last_name));
                $normalizedLast_name2 = mb_strtolower(strtolower($family_name));

                if ($user_cuil == $cuit && $normalizedName1 == $normalizedName2 && $normalizedLast_name1 = $normalizedLast_name2 ){
                    //datos consistentes sube a nivel 3
                    
                    $res_user_auth = $this->userService->setAuthType($user, "MIARGENTINA", "level_3");

					if ($res_user_auth) {

						return response()->json([
							'status' => true,
							'message' => 'Application Validated User Identity',
							'token' => $user->createToken("user_token", ['level_3'])->accessToken
						]);

					} else {

						return $this->errorService->databaseWriteError();

					}

                }else{

                    return $this->errorService->dataInconsistency();
                    
                }

            }else{
                return $this->errorService->badUser();
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


    public function validateFaceToFaceGetData (Request $request)
    {
        
        $request->validate([
            "cuil_citizen" => "required|numeric|regex:/^[0-9]{11}$/",
            "token" => "required|string",
        ]);
             
        $host = env('BASEUR_ER_WS_TOKEN');
               
        $response = Http::post($host, [
            '_tk' => $request['token'],
        ]);

        $responseBody = $response->body();


        if($responseBody == 1) {

            $user = $this->userService->getUser($request['cuil_citizen']);

            if ($user){

                $column_name = "USER_ID";
                $column_value = $user->id;
                $table = "USER_AUTHENTICATION";
                $user_auth = $this->genericRepository->getRow($table, $column_name, $column_value);
                
                if ($user_auth ){

                    $column_name = "USER_ID";
                    $column_value = $user->id;
                    $table = "USER_CONTACT";
                    $user_data = $this->genericRepository->getRow($table, $column_name, $column_value);

                    if ($user_data){

                        $user_data = [
                            "user" => $user,
                            "user_contact" => $user_data,
                            "user_leves_auth" => $user_auth->AUTH_LEVEL
                        ];
                
                        return response()->json([
                            'status' => true,
                            'message' => 'User data',
                            'user_data' => $user_data
                        ]);

                    }else{

                        $user_data = [
                            "user" => $user,
                            "user_leves_auth" => $user_auth->AUTH_LEVEL
                        ];

                        return response()->json([
                            'status' => true,
                            'message' => 'User data',
                            'user_data' => $user_data
                        ]);
                
                       
                    }    
    
                }else{
                        
                    return $this->errorService->validateEmail();
    
                }
    
                
    
            }else{
    
                return $this->errorService->badUser();
    
                }

        }else{

            return $this->errorService->badToken();

        }       

    }



    public function validateFaceToFaceCitizen(validateFaceToFaceCitizenRequest $request)
    {

        $validated = $request->validated();

        $host = env('BASEUR_ER_WS_TOKEN');
               
        $response = Http::post($host, [
            '_tk' => $validated['token'],
        ]);

        $responseBody = $response->body();

        if($responseBody == 1) {

            $user = $this->userService->getUser($validated['cuil_citizen']);

            if ($user){

                $validatedCollection = collect($validated);

                $filteredRequestUserContact = $validatedCollection->only([
                    'birthday', 
                    'cellphone_number', 
                    'department_id', 
                    'locality_id', 
                    'address_street', 
                    'address_number', 
                    'apartment'
                ])->toArray();
                                    
                $res_personal_data = $this->userService->setUserContact($user, $filteredRequestUserContact );
						
                if ($res_personal_data){

                    $user->name = $validated['name'];
                    $user->last_name = $validated['last_name'];
                    $user->save();

                    $res_user_auth = $this->userService->setAuthType($user, "PRESENCIAL", "level_3");

                    if ($res_user_auth) {

                        return response()->json([
                            'status' => true,
                            'message' => 'Presential Validated User Identity',
                        ]);

                    }else{

                        return $this->errorService->databaseReadError();
    
                    }
                            
                }else{

                    return $this->errorService->databaseReadError();
                }
                        
            }else{

                return $this->errorService->badUser();

            }

        }else{

            return $this->errorService->badToken();

        }
        
    }

    public function ActorRedirect(ActorRedirectRequest $request)
    {
        $validated = $request->validated();
        $host = env('BASEUR_ER_WS_TOKEN');
        $response = Http::post($host, [
            '_tk' => $validated['token'],
        ]);
        $responseBody = $response->body();
    
        if ($responseBody == 1) {
            $prs_id = $this->wsService->getPrs_id($validated['dni']);
            $user = null;
            $errorMessage = null;
    
            if ($prs_id == "bad dni") {
    
                $errorMessage = 'User not found or ERWS error';
                $cuil = $this->userService->getCuilFromDNI($validated['dni']);

                if ($cuil) {
                    $user = User::where('cuil', $cuil)->first();
                }

            } else {

                $user = User::where('prs_id', $prs_id)->first();

            }
            if ($user) {

                if ($user->email_verified_at == null) {

                    return response()->json([
                        'status' => false,
                        'message' => 'email validation still pending'
                    ], 400);

                }
    
                $column_name = "USER_ID";
                $column_value = $user->id;
                $table = "USER_AUTHENTICATION";
                $user_auth = $this->genericRepository->getRow($table, $column_name, $column_value);
    
                if (!empty($user_auth)) {
                    $token = $user->createToken('user_token', [$user_auth->AUTH_LEVEL])->accessToken;
                    $timestamp = now()->addDays(1);
                    $expires_at = date('M d, Y H:i A', strtotime($timestamp));
    
                    $column_name = "USER_ID";
                    $column_value = $user->id;
                    $table = "USER_CONTACT";
                    $user_data = $this->genericRepository->getRow($table, $column_name, $column_value);
    
                    $table = "USER_ACTORS";
                    $user_actor = $this->genericRepository->getRow($table, $column_name, $column_value);
                    $is_actor_empty = empty($user_actor);
                    $is_actor = !$is_actor_empty;
    
                    $user_data = [
                        "user" => $user,
                        "user_contact" => $user_data,
                        "is_actor" => $is_actor
                    ];
    
                    return response()->json([
                        'status' => true,
                        'message' => 'Login successful',
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_at' => $expires_at,
                        'user_data' => $user_data
                    ]);
                } else {
                    return $this->errorService->databaseReadError();
                }
            } else {
    
                return response()->json([
                    'status' => false,
                    'message' => $errorMessage
                ], 400);
                
            }
        } else {
            return $this->errorService->badToken();
        }
    }
}