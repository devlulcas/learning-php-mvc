<?php

// Carregando o autoloader
require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;
use \App\Utils\View;

// Constante que define a URL do projeto (pode variar para você)
define("URL", "http://localhost:2309");

// Carregando variáveis padrão
View::init([
  'URL' => URL
]);

// Inicia o Router
$router = new Router(URL);

// Requer as rotas das páginas
require __DIR__ . '/routes/pages.php';

// Envia as respostas 
$router->run()->sendResponse();
