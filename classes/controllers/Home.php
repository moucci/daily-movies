<?php

namespace classes\controllers;

use classes\Core;
use classes\Routes;

class Home extends MainController
{
    public function __construct()
    {
        //get number of page in url
        $nPage = Routes::getParams()[1] ?? 1;

        //render page
        MainController::render("home", [
            "title" => "Bienvenue sur Daily Movies",
            "items" => Articles::getAll($nPage ,7)
        ]);
    }
}