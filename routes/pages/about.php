<?php

use App\Http\Response;
use \App\Controller\Pages;

$router->get("/about", [
	function () {
		return new Response(200, Pages\About::getAbout());
	}
]);
