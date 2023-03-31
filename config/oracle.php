<?php

return [
	'oracle' => [
		'driver' => 'oracle',
		'tns' => env('DB_TNS'),
		'host' => env('DB_HOST'),
		'port' => env('DB_PORT'),
		'database' => env('DB_DATABASE'),
		'service_name' => env('DB_SERVICE_NAME'),
		'username' => env('DB_USERNAME'),
		'password' => env('DB_PASSWORD'),
		'charset' => env('DB_CHARSET'),
		'prefix' => env('DB_PREFIX'),
		'prefix_schema' => env('DB_SCHEMA_PREFIX'),
		'edition' => env('DB_EDITION'),
		'server_version' => env('DB_SERVER_VERSION'),
		'load_balance' => env('DB_LOAD_BALANCE'),
		'dynamic' => [],
	],
];

# * SID = identifies the database instance (database name + instance number). So if your database name is somedb and your instance number is 3, then your SID is somedb3.
# * Service Name = A "connector" to one or more instances. It is often useful to create additional service names in a RAC environment since the service can be modified to
#       use particular SIDs as primary or secondary connections, or to not use certain SIDs at all.
