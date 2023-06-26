<?php

namespace classes;

class Autoloader
{

    /** Methode to register class
     * @return void
     */
    static function register(): void
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /** methode  to include class
     * @param string $class_name
     * @return void
     */
    static function autoload(string $class_name): void
    {
        require_once $class_name . '.php';
    }

}