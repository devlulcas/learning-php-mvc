<?php

use App\Http\Response;
use \App\Controller\Pages;

$router->get("/", [
	function () {
		return new Response(200, Pages\Home::getHome());
	}
]);
