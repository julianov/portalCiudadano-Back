<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthGetTokenAutenticarRequest;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;

use App\Mail\PruebaEmail;
use Mail;


class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */
    public function getToken(Request $request): \Illuminate\Http\JsonResponse
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

            return response()->json($data, 200);
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
