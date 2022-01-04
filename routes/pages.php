<?php

use App\Http\Response;

/**
 * Estamos usando o namespace de Pages então onde tinhamos anteriormente apenas o nome da classe de dentro do namespace
 * \Pages como por exemplo Home, teremos uma chamada mais extensa como Pages\Home
 *  */

use \App\Controller\Pages;


// Rotas e suas funções para as páginas web
$router->get("/", [
  function () {
    return new Response(200, Pages\Home::getHome());
  }
]);

$router->get("/about", [
  function () {
    return new Response(200, Pages\About::getAbout());
  }
]);

$router->get("/testimonies", [
  function () {
    return new Response(200, Pages\Testimony::getTestimonies());
  }
]);

$router->post("/testimonies", [
  function ($request) {
    echo '<pre style="position: absolute; background: #000505; z-index:100; width:100%; color:#f06449; padding: 10px;">';
    print_r($request);
    echo '</pre>';
    return new Response(200, Pages\Testimony::getTestimonies());
  }
]);

$router->get("/pagina/{id}/{action}", [
  function($id, $action) {
    return new Response(200, "Página $id - $action");
  }
]);
