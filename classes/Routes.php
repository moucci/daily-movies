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

    private array $params = [];


    /**
     * explode url and push value on params array
     */
    public function __construct()
    {
        //explode  url
        $this->params = explode('/', str_replace(' ', '+', $_GET["url"]));

        //convert camelCase
        $this->params[0] = Core::dashesToCamelCase($this->params[0]);

        //if route empty
        //det by default home
        if (strlen($this->params[0]) === 0) $this->params[0] = 'home';
    }


    /**
     * Methode to check  if routes existe
     * @return bool|string
     */
    public function checkRoute(): bool|string
    {
        return (!array_key_exists($this->params[0], $this->routes)) ? 'NOT_FOUND' : true;
    }

    /**
     * Methode to check if route can be activate
     * @return bool|string
     */
    public function canActivate(): bool|string
    {
        return ($this->routes[$this->params[0]]) ? 'NEED_LOGIN' : true;
    }

    /**
     * Methode to return route
     * @return string
     */
    public function getRoute(): string
    {
        return $this->params[0];
    }


}