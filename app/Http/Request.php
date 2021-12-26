<?php

namespace App\Http;

class Request
{
  // GET / POST / ETC
  private string $httpMethod;

  // Route
  private string $uri;

  // Url params
  private array $queryParams = [];

  // $_POST
  private array $postVars = [];

  // Headers
  private array $headers = [];

  public function __construct()
  {
    // GET E POST SÃO SUPER GLOBALS 
    $this->queryParams = $_GET ?? [];
    $this->postVars = $_POST ?? [];
    // Obtem todos os headers, óbvio, mas é peculiar que a linguagem não siga uma convenção de nomes internamente
    $this->headers = getallheaders();
    // SERVER é outra super global
    $this->httpMethod = $_SERVER["REQUEST_METHOD"] ?? "";
    $this->uri = $_SERVER["REQUEST_URI"] ?? "";
  }

  // Getters
  public function getHttpMethod(): string
  {
    return $this->httpMethod;
  }

  public function getUri(): string
  {
    return $this->uri;
  }

  public function getHeaders(): array
  {
    return $this->headers;
  }

  public function getPostVars(): array
  {
    return $this->postVars;
  }

  public function getQueryParam(): array
  {
    return $this->queryParams;
  }
}
