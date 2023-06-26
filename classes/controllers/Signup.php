<?php

namespace classes\controllers;

class Signup extends MainController
{

    public function __construct()
    {
        MainController::render("inscription") ;
    }

}