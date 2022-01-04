<?php

// Carregando o autoloader
require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;
use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;

// Constante que define a URL do projeto (pode variar para você)
$envUrl = getenv("DEVURL");
define("URL", $envUrl);

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
