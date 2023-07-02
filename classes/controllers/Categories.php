<?php

namespace classes\controllers;

use classes\Core;
use classes\Db;
use classes\Routes;
use PDO;
use PDOException;


class Categories extends MainController
{

    private string $categorie;

    public function __construct()
    {

        //get params in url
        $this->categorie = Routes::getParams()[1] ?? null;

        //get number of page in url
        $nPage = Routes::getParams()[2] ?? 1;
        $nPage = is_numeric($nPage) ? $nPage : 1;

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
//        $title = (isset($data["articles"][0]->title)) ? '$data["articles"][0]->title' : htmlspecialchars($this->categorie);
        MainController::render("categories", [
            "title" => "Liste des article dans la catégorie " . htmlspecialchars($this->categorie) . " | Daily Movies",
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

    /**
     * Methode to add categorie  for article
     * @param array $newCats
     * @param int $idArticle
     * @return bool|string
     */
    public static function setArticleCategories(array $newCats, int $idArticle): bool|string
    {

        //check if categorie is not empty
        if (empty($newCats)) return true;

        $db = Db::getDb();
        //insert list of car
        try {
            $qInsertCat = "INSERT INTO article_categories (article_id, categorie_id) VALUES (:article_id, :categorie_id)";
            //start transaction
            $db->beginTransaction();
            $req = $db->prepare($qInsertCat);
            // bind value and execute query
            foreach ($newCats as $idCat) {
                $req->bindParam(':article_id', $idArticle, PDO::PARAM_INT);
                $req->bindParam(':categorie_id', $idCat, PDO::PARAM_INT);
                $req->execute();
            }
            // end transaction
            $db->commit();
            return true;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

    /**
     * Methode to remove categorie for article
     * @param array $oldCtas
     * @param int $idArticle
     * @return bool|string
     */
    public static function unSetArticleCategories(array $oldCtas, int $idArticle): bool|string
    {

        //check if categorie is not empty
        if (empty($oldCtas)) return true ;

        $db = Db::getDb();

        //insert list of car
        try {
            $qInsertCat = "DELETE FROM article_categories WHERE  article_id = :article_id and  categorie_id = :categorie_id";
            //start transaction
            $db->beginTransaction();
            $req = $db->prepare($qInsertCat);

            // bind value and execute query
            foreach ($oldCtas as $idCat) {
                $req->bindParam(':article_id', $idArticle, PDO::PARAM_INT);
                $req->bindParam(':categorie_id', $idCat, PDO::PARAM_INT);
                $req->execute();
            }

            // end transaction
            $db->commit();
            return true;
        } catch (PDOException $error) {
            return $error->getMessage();
        }
    }

}