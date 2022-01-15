<?php

return [
	'driver' => getenv('DB_DRIVER'),
	'host'   => getenv('DB_HOST'),
	'name'   => getenv('DB_NAME'),
	'user'   => getenv('DB_USER'),
	'pass'   => getenv('DB_PASSWORD'),
	'port'   => getenv('DB_PORT'),
];
