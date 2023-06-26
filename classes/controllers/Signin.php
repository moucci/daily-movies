<?php

namespace classes\controllers;

class Signin extends MainController
{


    public function __construct()
    {
        MainController::render("connexion") ;
    }

}