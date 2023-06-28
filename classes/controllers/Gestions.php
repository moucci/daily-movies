<?php

namespace classes\controllers;

use classes\Core;
use classes\Routes ; 

class Gestions extends MainController
{
    public function __construct()
    {

        Core::var_dump_pre(Routes::getParams()[0]) ;
        MainController::render("gestions") ;
    }
}