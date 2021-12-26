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

$router->get("/pagina/{id}/{action}", [
  function($id, $action) {
    return new Response(200, "Página $id - $action");
  }
]);
