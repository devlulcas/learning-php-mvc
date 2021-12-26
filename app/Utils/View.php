<?php

namespace App\Utils;

class View
{
  /**
   * Algumas das variáveis do sistema são padronizadas, como a URL por exemplo
   */
  private static array $defaultVars = [];
  public static function init(array $vars = [])
  {
    self::$defaultVars = $vars;
  }

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
    // Método estático que traz o HTML como string
    $contentView = self::getContentView($view);
    /**
     * Unimos as variáveis padrão (URL e afins) com as que são definidas para cada página.
     * No PHP 8.1 podemos utilizar os três pontos para espalhar os elementos de cada array em um novo array
     * ... = spread operator
     * $mergedVars = [...self::$defaultVars, ...$vars];
     * 
     * Para outras versões podemos usar o array_merge
     */
    $mergedVars = array_merge(self::$defaultVars, $vars);
    // Obter o "nome" das variáveis que serão substituídas do HTML
    $keys = array_keys($mergedVars);
    // Função lambda que será responsável por criar os placeholder que serão substitídos do HTML
    $createPlaceholder = function ($key) {
      return "{{" . $key . "}}";
    };
    // Aplicando a lambda em cada um dos elementos do array $keys, gerando um array de elementos neste estilo: "{{key}}"
    $placeholderKeys = array_map($createPlaceholder, $keys);
    // Substituir placeholders por variáveis reais
    $parsedContentView = str_replace($placeholderKeys, array_values($mergedVars), $contentView);
    // Retorna view com conteúdo novo
    return $parsedContentView;
  }
}
