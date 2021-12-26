<?php

namespace App\Utils;

class View
{
  /**
   * Verifica se o nome da view (arquivo html) passado existe e caso exista o arquivo html é lido e retornado como uma string, 
   * caso não exista uma string vazia é retornada
   */
  private static function getContentView(string $view): string
  {
    $file = __DIR__ . "/../../resources/view/$view.html";
    return file_exists($file) ? file_get_contents($file) : '';
  }

  /**
   * Renderiza o conteúdo das páginas. 
   * Basicamente chamamos o método estático que lê o arquivo html e procuramos, na string retornada, trechos que batam com as 
   * chaves do array associativo recebido como parametro . 
   * Para facilitar a utilização desse padrão no html utilizamos um formato especifico para determinar o que é conteúdo normal 
   * e o que deve ser substituído com a utilização de duas chaves: "{{}}".
   */
  public static function render(string $view, array $vars = []): string
  {
    // Método estático com o HTML
    $contentView = self::getContentView($view);

    // Obter variáveis que serão substituídas do HTML
    $keys = array_keys($vars);
    $placeholderKeys = array_map(function ($key) {
      return "{{" . $key . "}}";
    }, $keys);

    // Substituir placeholders por variáveis reais
    $parsedContentView = str_replace($placeholderKeys, array_values($vars), $contentView);

    // Retorna view com conteúdo novo
    return $parsedContentView;
  }
}
