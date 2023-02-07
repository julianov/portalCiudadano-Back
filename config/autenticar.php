<?php

return [
    'client_id' => env('AUTENTICAR_CLIENT_ID', 'appentrerios'),
    'realm' => env('AUTENTICAR_REALM', 'appentrerios-afip'),
    'redirect_uri' => env('AUTENTICAR_REDIRECT_URI', 'http://localhost'),
    'base_url' => env('AUTENTICAR_BASE_URL', 'https://tst.autenticar.gob.ar/auth/realms/'),
    'base_url_api' => config('autenticar.base_url') . config('autenticar.realm') . '/',
    'grant_type' => env('AUTENTICAR_GRANT_TYPE', 'authorization_code'),
    'secret' => env('AUTENTICAR_SECRET', 'secret')
];
