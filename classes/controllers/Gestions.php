<?php

namespace classes\controllers;

use classes\controllers\Articles;
use classes\Core;
use classes\Routes;
use PDO;
use PDOException;

class Gestions extends MainController
{
    public function __construct()
    {
        // Get route
        $route = Routes::getParams()[1] ?? '';
        switch (strtolower($route)) {
            case "edit":
                $this->editArticle();
                break;
            case "new":
                $this->newArticle();
                break;
            case "categories":
                $this->newCategories();
                break;
            default:
                // Get number of page in URL
                $nPage = (isset(Routes::getParams()[1]) && is_numeric(Routes::getParams()[1])) ? Routes::getParams()[1] : 1;
                // Render the view for managing articles
                MainController::render("gestions.articles", [
                    "title" => "Modifier les articles | Daily Movies",
                    "items" => Articles::getAll($nPage, 4)
                ]);
                die;
                break;
        }
    }


    /**
     * Route new article
     * @return void
     */
    private function newArticle()
    {
        // Add a new article
        $articleResponse = Articles::addArticle($_POST);
        if ($articleResponse['process']) {
            header('Location: /gestions');
        } else {
            // Render the view with the response
            MainController::render('gestions.add', [
                "title" => "Ajouter un article | Daily Movies",
                "response" => $articleResponse
            ]);
        }
        die;
    }

    /**
     * Route edit Article
     * @return void|null
     */
    private function editArticle()
    {


        // Get the article ID from the URL parameters
        $idArticle = Routes::getParams()[2] ?? null;

        // Check if the article ID is numeric
        if (!is_numeric($idArticle)) {
            return header('Location: /gestions');
        }

        // Get the article data by ID
        $articleResponse = Articles::getById($idArticle);

        // Check if the article exists
        if (!$articleResponse['article']->title) {
            return header('Location: /notFound');
        }

        // get categoroe in  allowed
        $categories = Categories::getAll();

        // Get the list of categories for the article
        $listCats = explode(',', $articleResponse['article']->idCats ?? '');

        // Check if $_POST is empty
        if (empty($_POST) && empty($_FILES['image']['name'])) {
            // Set $_POST with the article data for updating
            $_POST["title"] = $articleResponse['article']->title;
            $_POST["slug"] = $articleResponse['article']->slug;
            $_POST["content"] = $articleResponse['article']->content;
            $_POST["file"] = $articleResponse['article']->image;
            //bind categorie
            $_POST['categories'] = $listCats;
            $_POST["edit"] = true;

            //add catergorie to response
            $articleUpdateResponse["categories"] = $categories;

            // Render the view with the response
            MainController::render('gestions.add', [
                "title" => "Mettre à jour  un article | Daily Movies",
                "response" => $articleUpdateResponse,
            ]);
            die();
        }

        // Update the article
        $articleUpdateResponse = Articles::updateArticle($_POST, (array)$articleResponse['article']);

        if ($articleUpdateResponse['process']) {
            header('Location: /article/' . $idArticle);
        } else {
            //add catergorie to response
            $articleUpdateResponse["categories"] = $categories;
            // Render the view with the response
            MainController::render('gestions.add', [
                "title" => "2 Mettre à jour  un article | Daily Movies",
                "response" => $articleUpdateResponse
            ]);
            die;
        }

    }


    private function newCategories(){

        // Check if $_POST is empty
        if (empty($_POST)) {
            MainController::render('gestions.categories' , [
                "title"=> "Ajouter ou supprimer une categorie | Daily Movies ",
                "categories" => Categories::getAll()
            ]);
        }else{
            MainController::render('gestions.categories' , [
                "title"=> "Ajouter ou supprimer une categorie | Daily Movies ",
                "categories" => Categories::getAll()
            ]);
        }


    }



    /**
     * $db object
     * $user_id string -> l'id user
     * $title string  -> le titre
     * $content string -> le contenu
     * $image string -> le nom de l'image
     * $idcategorie int -> l'id de la catégorie
     */

    public static function addcategorie(object $db, int $user_id, string $title, string $content, string $image, int $idcategorie)
    {

        //on récupère l'id de l'article
        $query = "SELECT * FROM `articles` WHERE `user_id` = :user_id AND `title` = :title AND `content` = :content AND `image` = :image";

        $req = $db->prepare($query);


        $req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $req->bindParam(':title', $title, PDO::PARAM_STR);
        $req->bindParam(':content', $content, PDO::PARAM_STR);
        $req->bindParam(':image', $image, PDO::PARAM_STR);

        try {
            $req->execute();
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }

        $articleid = $req->fetch(PDO::FETCH_ASSOC);

        //l'ajout dans article_categories
        $query = "INSERT INTO `article_categories` (article_id, categorie_id) VALUES (:articleid, :idcategorie)";

        $req = $db->prepare($query);


        $req->bindParam(':articleid', $articleid['id'], PDO::PARAM_INT);
        $req->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);

        try {
            $req->execute();
            return [
                'process' => true,
            ];
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }


        $req->closeCursor();
    }


    /**
     * $db object
     * $name le nom de la catégorie que l'on veut créer
     */
    public static function createcategorie(object $db, string $name)
    {

        $query = "INSERT INTO `categories` (name, slug) VALUES (:name, :slug)";

        $req = $db->prepare($query);

        $req->bindParam(':name', $name, PDO::PARAM_STR);

        $slug = Gestions::slugify($name);
        $req->bindParam(':slug', $slug, PDO::PARAM_STR);

        try {
            $req->execute();
            return [
                'process' => true,
                'message' => 'La catégorie a bien été ajoutée.',
            ];
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }

        $req->closeCursor();
    }

    public static function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }



}
