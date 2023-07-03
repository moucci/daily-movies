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
        $req = $db->query("SELECT id ,  name as name , slug   FROM categories ORDER BY id DESC ");
        $categories = $req->fetchAll(PDO::FETCH_OBJ);
        $totalResults = $req->rowCount();
        $req->closeCursor();
        return $totalResults == 0 ? [] : $categories;
    }

    /**
     * @param string $name
     * @param $slug
     * @return bool|array
     */
    public static function add(string $name, $slug): bool|array
    {
        $errors = [];

        //check slug
        $checkSlug = Core::checkSlug($slug);
        if ($checkSlug !== true) $errors['slug'] = $checkSlug;
        $checkSlugExist = $checkSlug === true ? Categories::checkSlugExist($slug) : null;
        if (isset($checkSlugExist['slug'])) $errors['slug'] = "Ce slug existe déja pour une autre catégorie";

        //check name
        $checkName = Core::checkName('nom', $name);
        if ($checkName !== true) $errors['name'] = $checkName;

        //check error
        if (!empty($errors)) return $errors;

        //insert into db
        $db = Db::getDb();
        $q = "INSERT INTO  categories (name, slug) VALUES (:name , :slug)";
        $req = $db->prepare($q);
        $name = strtolower($name);
        $slug = strtolower($slug);
        $req->bindParam(":name", $name);
        $req->bindParam(":slug", $slug);
        try {
            $req->execute();
        } catch (PDOException $e) {
            error_log($e);
            $errors['autre'] = "Une erreur est survenue , merci de réessayer plus tard";
            return $errors;
        }

        return true;
    }

    /**
     * Methode to check if slug existe on table categorie
     * @param string $slug
     * @return array|string array if exist , error message if sql query wrong
     */
    private static function checkSlugExist(string $slug): array|string
    {
        $db = Db::getDb();
        $query = "SELECT slug , id FROM categories WHERE slug = :slug LIMIT 1";
        $req = $db->prepare($query);
        $slug = strtolower($slug);
        $req->bindParam(":slug", $slug);
        if (!$req->execute()) return "une erreur est survenue lors de la vérification du slug ";
        return $req->fetch();
    }


    /**
     * @param array $cats
     * @return string[]
     */
    public static function delete(array $cats): bool|array
    {

        //check if categorie is not empty
        if (empty($cats)) return false ;

        $db = Db::getDb();

        //insert list of car
        try {
            $qDeleteCat = "DELETE FROM categories WHERE  id = :id ";
            //start transaction
            $db->beginTransaction();
            $req = $db->prepare($qDeleteCat);

            // bind value and execute query
            foreach ($cats as $idCat) {
                $req->bindParam(':id', $idCat, PDO::PARAM_INT);
                $req->execute();
            }
            // end transaction
            $db->commit();
            return true;
        } catch (PDOException $error) {
            if ($error->getCode() === '23000') {
                return [
                    "categories" => "Impossible de supprimer les catégories sélectionnées car elles sont encore liées à des articles. Veuillez effectuer les modifications nécessaires avant de réessayer."
                ];
            } else {
                return [
                    "autre" => "Une erreur est survenue lors de la tentative de supression des categorie réessyer plus tard "
                ];
            }
        }


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
    public static function unSetArticleCategories(array $oldCats, int $idArticle): bool|string
    {

        //check if categorie is not empty
        if (empty($oldCats)) return true;

        $db = Db::getDb();

        //insert list of car
        try {
            $qDeleteCat = "DELETE FROM article_categories WHERE  article_id = :article_id and  categorie_id = :categorie_id";
            //start transaction
            $db->beginTransaction();
            $req = $db->prepare($qDeleteCat);

            // bind value and execute query
            foreach ($oldCats as $idCat) {
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