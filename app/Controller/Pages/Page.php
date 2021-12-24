<?php

namespace App\Controller\Pages;

use \App\Utils\View;

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
