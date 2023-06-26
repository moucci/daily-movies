<?php

//start session
session_start();

//include auto loader
require_once 'classes/Autoloader.php';

use classes\Autoloader;
use classes\controllers\MainController;
use classes\Routes;

//register autoload
Autoloader::register();

//set route params
$routes = new Routes() ;

//check if route existe
if ($routes->checkRoute() === "NOT_FOUND") {
    new MainController("notFound");
    die();
}

//check if route  can activate
if($routes->canActivate() === "NEED_LOGIN"){
    new MainController("connexion" , true);
    die();
}

//get page
$page=  $routes->getRoute();

//return page
new MainController($page) ;





