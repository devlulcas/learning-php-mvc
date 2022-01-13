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

    public static function postSandbox(array $formData) {
        echo '<pre style="position: absolute; background: #ffffff; z-index:100; width:100%; color:#f06449; padding: 10px;">';
        print_r($formData);
        echo '</pre>';
    }
}
