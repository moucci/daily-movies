<?php

namespace classes\controllers;

class Home extends MainController
{
    public function __construct()
    {
        MainController::render("home") ;
    }
}