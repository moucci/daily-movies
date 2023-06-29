<?php

namespace classes\controllers;

use classes\Core;
use classes\Routes ; 

class Gestions extends MainController
{
    public function __construct()
    {
        MainController::render("gestions") ;
    }
}