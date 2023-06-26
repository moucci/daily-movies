<?php

namespace classes;

use PDO;

/**
 * Created by PhpStorm.
 * User: MOUCCI
 */
class  Config
{
    /** server sql name
     * @var string
     */
    private $MYSQL_SERVEUR = 'localhost:3306';

    /** user sql name
     * @var string
     */
    private $MYSQL_USER = 'root';

    /** mdp sql
     * @var string
     */
    private $MYSQL_MDP = '';

    /** sql database name
     * @var string
     */
    private $MYSQL_BASE = 'daily-movies';

    //version app
    const appVersion = '0.1';


    public function __construct()
    {
    }

    /** Metode  try to open connecion with mysql server
     * @return string|PDO
     */
    public function getDb():string | \PDO
    {
        try {
            $dsn = "mysql:host={$this->MYSQL_SERVEUR};dbname={$this->MYSQL_BASE}";
            $pdo = new PDO($dsn, $this->MYSQL_USER, $this->MYSQL_MDP);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // return  instance of PDO
            return $pdo;
        } catch (PDOException $e) {
            //if we have errir connection return error
            return 'Erreur de connexion : ' . $e->getMessage() ;
        }
    }



}

