<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

/**
 * Estende Page para utilizar o método getPage passando o conteúdo resultante de View::render, que por sua vez recebe os
 * dados proveniente do modelo Organization.
 */
class Home extends Page
{
  public static function getHome(): string
  {
    $dataOrganization = new Organization;

    $infos = [
      "name" => $dataOrganization->name,
      "site" => $dataOrganization->site,
      "description" => $dataOrganization->description
    ];

    $content = View::render("pages/home", $infos);

    return parent::getPage("Homepage", $content);
  }
}
