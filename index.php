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
    new MainController("notFound") ;
    die();
}

//check if route  can activate
if($routes->canActivate() === "NEED_LOGIN"){
    header('Location: /connexion');
    die();
}

//get name page
$page=  strtolower($routes->getRoute());

//check if route need user logout to used
if(in_array($page , ["connexion" , "inscription"]) && isset($_SESSION['is_connected'])){
    header('Location: /gestions');
    die();
}

//return page
new MainController($page) ;





