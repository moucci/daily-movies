<?php

namespace classes\controllers;
use classes\Db;
use PDO;


class Categories extends MainController
{

    /**
     * Methode to return all categorie on table caterogies
     * @return array
     */
    public static function getAll(): array
    {
        //GET DB CONNEXION
        $db = Db::getDb();
        $req = $db->query("SELECT id ,  name as name   FROM categories");
        $categories = $req->fetchAll(PDO::FETCH_OBJ);
        $totalResults = $req->rowCount();
        $req->closeCursor();
        return $totalResults == 0 ? [] : $categories;
    }

}