<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthGetTokenAutenticarRequest;
use GuzzleHttp\Exception\GuzzleException;

class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */
    public function getToken(AuthGetTokenAutenticarRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated();
        $cuil = $request->cuil;
        $code = $request->code;

        $client = new \GuzzleHttp\Client();
        $url = config("autenticar.base_url_api");
        $response = $client->request('POST', $url."/protocol/openid-connect/token", [
            'form_params' => [
                "grant_type" => config('autenticar.grant_type'),
                "client_id" => config('autenticar.client_id'),
                "client_secret" => config('autenticar.secret'),
                "code" => $code,
                "redirect_uri" => config('autenticar.redirect_uri'),
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];
        $refreshToken = $data['refresh_token'];

        return response()->json([
            "access_token" => $accessToken,
            "refresh_token" => $refreshToken,
        ]);
    }
}
