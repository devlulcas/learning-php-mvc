<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Controller\Pages\Page;

class Sandbox extends Page
{
    public static function getSandbox()
    {
        $infos = [
            "sandboxDescription" => "Uma página para testar diversas barbaridades"
        ];

        $content = View::render("pages/sandbox", $infos);

        return parent::getPage("Sandbox", $content);
    }
}
