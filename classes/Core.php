<?php

namespace classes;

class Core
{

    /**
     * convertit string with "-" to CamelCase
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @return array|string
     */
    public static function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false): array|string
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        if (!empty($str) && !$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

}