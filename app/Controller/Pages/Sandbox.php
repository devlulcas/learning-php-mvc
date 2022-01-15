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

    public static function postSandbox(array $formData)
    {
        $callAnimeApi = file_get_contents("https://animechan.vercel.app/api/random");
        $quoteJson = json_decode($callAnimeApi);
        
        $callCatApi = file_get_contents("https://api.thecatapi.com/v1/images/search?size=400");
        $catJson = json_decode($callCatApi);
        $catUrl = $catJson[0]->url;

        echo '<php-debug>';
        print_r($quoteJson->quote);
        echo "<br/>";
        echo "<img src=\"$catUrl\"/>";
        echo '</php-debug>';
        echo '<script src="https://gitcdn.link/cdn/devlulcas/floatingDebugWindow/main/floatingDebug.js" defer></script>';
    }
}
