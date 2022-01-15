<?php

// Carregando as configs
require_once __DIR__ . '/config/config.php';

use \App\Http\Router;
use \App\Utils\View;

// Carregando variáveis padrão
View::init([
  'URL' => URL
]);

// Inicia o Router
$router = new Router(URL);

// Requer as rotas das páginas
require __DIR__ . '/routes/routes.php';

// Envia as respostas 
$router->run()->sendResponse();
