<?php

use App\Http\Response;
use \App\Controller\Pages;


$router->get("/sandbox", [
	function () {
		return new Response(200, Pages\Sandbox::getSandbox());
	}
]);


$router->post("/sandbox", [
	function ($request) {
		return new Response(200, Pages\Sandbox::postSandbox($request->getPostVars()));
	}
]);
