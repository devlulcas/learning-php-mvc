<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Controller\Pages\Page;

class Testimony extends Page
{
    public static function getTestimonies(): string
    {
        $infos = [
            "name" => "Testimonies"
        ];

        $content = View::render("pages/testimonies", $infos);

        return parent::getPage("Depoimentos", $content);
    }
}
