<?php

namespace classes\controllers;

use classes\Core;
use classes\Db;
use classes\Routes;
use PDO;


class Categories extends MainController
{

    private string $categorie;

    public function __construct()
    {

        //get params in url
        $this->categorie = Routes::getParams()[1] ?? null;

        //get number of page in url
        $nPage = Routes::getParams()[2] ?? 1;

        //check  params
        if (empty($this->categorie)) {
            MainController::render("notFound", [
                "title" => "La page que vous avez demandée n'existe pas",
            ]);
            die;
        }

        //get data
        $data = Articles::getAllByCategorie($this->categorie, $nPage, 6);

        //check data
//        $view = (!empty($data["articles"])) ? "categories" : "notFound";
        $title = (isset($data["articles"][0]->title)) ? $data["articles"][0]->title : htmlspecialchars($this->categorie);
        MainController::render("categories", [
            "title" => "{$title} | Daily Movies",
            "items" => $data
        ]);
    }

    /**
     * Methode to return all categorie on table caterogies
     * @return array
     */
    public static function getAll(): array
    {
        //GET DB CONNEXION
        $db = Db::getDb();
        $req = $db->query("SELECT id ,  name as name , slug   FROM categories");
        $categories = $req->fetchAll(PDO::FETCH_OBJ);
        $totalResults = $req->rowCount();
        $req->closeCursor();
        return $totalResults == 0 ? [] : $categories;
    }

}