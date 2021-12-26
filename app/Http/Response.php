<?php

namespace App\Http;

class Response
{
  // Código do status http
  private int $httpCode = 200;

  // Cabeçalhos HTTP para retornar
  private array $headers = [];

  // Enviamos html por padrão 
  private string $contentType = "text/html";

  /**
   * Conteúdo contido na resposta
   * @var mixed
   * */
  private $content;

  public function __construct($httpCode, $content, $contentType = "text/html")
  {
    $this->httpCode = $httpCode;
    $this->content = $content;
    $this->setContentType($contentType);
  }

  /**
   * Recebe uma string para o tipo de conteúdo, que por padrão é HTML ou texto simples, mas que pode receber
   * qualquer tipo de conteúdo, viabilizando a criação de APIs também.
   */
  public function setContentType(string $contentType)
  {
    $this->contentType = $contentType;
    // Content-Type é um header http, então chamamos a função local addHeader aqui também
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * Recebe um par chave e valor com o header a ser adicionado na estrutura da response.
   */
  public function addHeader(string $key, string $value)
  {
    $this->headers[$key] = $value;
  }

  private function sendHeaders()
  {
    // Define o status da resposta da nossa aplicação utilizando a função nativa do PHP chamada http_response_code
    http_response_code($this->httpCode);
    // Enviar cada um dos headers no array utilizando a função nativa do PHP chamada header
    foreach ($this->headers as $headerName => $headerValue) {
      header("$headerName: $headerValue");
    }
  }

  /**
   * Envia um objeto de resposta.
   * O objeto possui headers e conteúdo.
   */
  public function sendResponse()
  {
    // Enviar headers
    $this->sendHeaders();
    // Enviar conteúdo
    switch ($this->contentType) {
      case 'text/html':
        echo $this->content;
        exit;
      default:
        # code...
        break;
    }
  }
}
