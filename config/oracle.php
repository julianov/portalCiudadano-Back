<?php

return [
<<<<<<< HEAD
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=database)(PORT=1521))) (CONNECT_DATA=(SERVER=DEDICATED)(SID=xe)))'),
        'host'           => env('DB_HOST', 'oracle-db'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', 'xe'),
        'service_name'   => env('DB_SERVICE_NAME', 'PORTALCIUDADANO'),
        'username'       => env('DB_USERNAME', 'system'),
        'password'       => env('DB_PASSWORD', 'oracle'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'edition'        => env('DB_EDITION', 'ora$base'),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
        'load_balance'   => env('DB_LOAD_BALANCE', 'yes'),
        'dynamic'        => [],
    ],
];

# * SID = identifies the database instance (database name + instance number). So if your database name is somedb and your instance number is 3, then your SID is somedb3.
# * Service Name = A "connector" to one or more instances. It is often useful to create additional service names in a RAC environment since the service can be modified to 
#       use particular SIDs as primary or secondary connections, or to not use certain SIDs at all.
=======
	'oracle' => [
		'driver' => 'oracle',
		'tns' => env('DB_TNS',
			'(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=database)(PORT=1521))) (CONNECT_DATA=(SERVER=DEDICATED)(SID=xe)))'),
		'host' => env('DB_HOST', 'oracle-db'),
		'port' => env('DB_PORT', '1521'),
		'database' => env('DB_DATABASE', 'xe'),
		'service_name' => env('DB_SERVICE_NAME', 'PORTALCIUDADANO'),
		'username' => env('DB_USERNAME', 'system'),
		'password' => env('DB_PASSWORD', 'oracle'),
		'charset' => env('DB_CHARSET', 'AL32UTF8'),
		'prefix' => env('DB_PREFIX', ''),
		'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
		'edition' => env('DB_EDITION', 'ora$base'),
		'server_version' => env('DB_SERVER_VERSION', '11g'),
		'load_balance' => env('DB_LOAD_BALANCE', 'yes'),
		'dynamic' => [],
	],
];

# * SID = identifies the database instance (database name + instance number). So if your database name is somedb and your instance number is 3, then your SID is somedb3.
# * Service Name = A "connector" to one or more instances. It is often useful to create additional service names in a RAC environment since the service can be modified to
#       use particular SIDs as primary or secondary connections, or to not use certain SIDs at all.
>>>>>>> e40bfe757f261588605a6116f2891d17defade28
