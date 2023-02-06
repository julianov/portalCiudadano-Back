<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;

class AuthController extends Controller
{

    /**
     * @throws GuzzleException
     */
    public function getToken(AuthGetTokenAutenticarRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated();

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', config("autenticar.base_url") . 'protocol/openid-connect/token', [
            'form_params' => [
                "grant_type" => config('autenticar.grant_type'),
                "client_id" => config('autenticar.client_id'),
                "client_secret" => $request->client_secret,
                "code" => $request->code,
                "redirect_uri" => config('autenticar.redirect_uri'),
            ]
        ]);
        return response()->json(json_decode($response->getBody()->getContents(), true));
    }

    public function getUrl(\Request $request): \Illuminate\Http\JsonResponse
    {
        $url = config("autenticar.base_url_api");
        $client = new \GuzzleHttp\Client();

        $response = $client->request("GET", $url."protocol/openid-connect/auth", [
            "query" => [
                "response_type" => "code",
                "client_id" => "appentrerios",
                "redirect_uri" => config('autenticar.redirect_uri'),
                "scope" => "openid",
            ]
        ]);

        return response()->json(json_decode($response->getBody()->getContents(), true));
    }
}
