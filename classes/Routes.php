<?php

namespace classes;

class Routes extends Core
{


    /**
     * Lists of route authorized
     * @var array
     */
    private array $routes = [
        "home" => false,
        "article" => false,
        "gestions" => true,
        "connexion" => false,
        "inscription" => false
    ];

    private static array $params = [];


    /**
     * explode url and push value on params array
     */
    public function __construct()
    {
        //explode  url
        self::$params = explode('/', str_replace(' ', '+', $_GET["url"]));

        //convert camelCase
        self::$params[0] = Core::dashesToCamelCase(self::$params[0]);

        //if route empty
        //det by default home
        if (strlen(self::$params[0]) === 0) self::$params[0] = 'home';
    }


    /**
     * Methode to check  if routes existe
     * @return bool|string
     */
    public function checkRoute(): bool|string
    {
        return (!array_key_exists(self::$params[0], $this->routes)) ? 'NOT_FOUND' : true;
    }

    /**
     * Methode to check if route can be activate
     * @return bool|string
     */
    public function canActivate(): bool|string
    {
        return ($this->routes[self::$params[0]] && !isset($_SESSION["is_connected"])) ? 'NEED_LOGIN' : true;
    }

    /**
     * Methode to return route
     * @return string
     */
    public function getRoute(): string
    {
        return self::$params[0];
    }

     /**
     * Methode to return params in route 
     * @return array
     */
    public static function getParams(): array
    {
        return  self::$params ;
    }




}