
<?php

use App\Http\Response;
use \App\Controller\Pages;

$router->get("/testimonies", [
	function () {
		return new Response(200, Pages\Testimony::getTestimonies());
	}
]);

$router->post("/testimonies", [
	function ($request) {
		return new Response(200, Pages\Testimony::getTestimonies());
	}
]);
