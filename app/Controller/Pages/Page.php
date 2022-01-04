<?php

namespace App\Controller\Pages;

use \App\Utils\View;

/**
 * Os três métodos dessa classe fazem basicamente a mesma coisa: chamam a classe View
 * getHeader lê o arquivo header.html e getFooter lê o arquivo footer.html, todas as views que utilizam o template
 * page.html estendem esta classe. 
 * 
 * O método getPage passa o conteúdo de header.html e footer.html para suas posições e recebe também dois atributos
 * title e content onde title é o atributo <title></title> e content é o conteúdo html de outras views. 
 */
class Page
{
  public static function getHeader(): string
  {
    return View::render("pages/header");
  }

  public static function getPage(string $title, string $content): string
  {
    $infos = [
      "title" => $title,
      "header" => self::getHeader(),
      "content" => $content,
      "footer" => self::getFooter()
    ];

    return View::render("pages/page", $infos);
  }

  public static function getFooter(): string
  {
    return View::render("pages/footer");
  }
}
