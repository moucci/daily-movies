<?php

namespace classes;


use PDO;

/**
 * Created by PhpStorm.
 * User: MOUCCI
 */
class  Db
{
    /** server sql name
     * @const string
     */
    const MYSQL_SERVEUR = 'localhost:3306';

    /**
     * user sql name
     * @const string
     */
    const MYSQL_USER = 'root';

    /**
     * mdp sql
     * @const string
     */
    const MYSQL_MDP = '';

    /**
     * sql database name
     * @const  string
     */
    const MYSQL_BASE = 'daily-movies';


    /**
     *
     * @var PDO|null $InstanceDb
     */
    private static PDO|null $InstanceDb = null;


    public function __construct()
    {
    }

    /** Metode  try to open connecion with mysql server
     * @return string|PDO
     */
    public static function getDb(): string|PDO
    {
        //check if  have ready pdo instance
        if (self::$InstanceDb instanceof PDO) return self::$InstanceDb;

        try {
            $dsn = "mysql:host=" . self::MYSQL_SERVEUR . ";dbname=" . self::MYSQL_BASE;
            $pdo = new PDO($dsn, self::MYSQL_USER, self::MYSQL_MDP);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // return  instance of PDO
            return self::$InstanceDb = $pdo;
        } catch (PDOException $e) {
            //if we have errir connection return error
            die ('Erreur de connexion : ' . $e->getMessage());
        }
    }


}

