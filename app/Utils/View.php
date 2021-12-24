<?php

namespace App\Utils;

use function PHPSTORM_META\map;

class View
{
  // Pega o conteúdo das páginas
  private static function getContentView(string $view): string
  {
    $file = __DIR__ . "/../../resources/view/$view.html";
    return file_exists($file) ? file_get_contents($file) : '';
  }

  // Renderiza o conteúdo das páginas
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
