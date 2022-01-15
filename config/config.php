<?php

// Carregando o autoloader
require __DIR__ . '/../vendor/autoload.php';

use \WilliamCosta\DotEnv\Environment;
use App\Database\Database;

// Carrega as variáveis globais do projeto
Environment::load(__DIR__ . "/../");

// Constante que define a URL do projeto (pode variar para você)
$envUrl = getenv("DEVURL");
define("URL", $envUrl);

// Configuração do banco de dados
$envDatabase = require __DIR__ . "/database.php";
Database::config($envDatabase);
