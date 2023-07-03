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


    private function newCategories()
    {

        // Check if $_POST is empty
        if (empty($_POST)) {
            MainController::render('gestions.categories', [
                "title" => "Ajouter ou supprimer une categorie | Daily Movies ",
                "categories" => Categories::getAll()
            ]);

            die;
        }


        // try to remove  cats if  had cats
        $removeCatProcess = !empty($_POST['categories']) ?
            Categories::delete($_POST['categories']) : [] ;

        // try to add categorie if no empty
        $addCatProcess = (!empty($_POST['slug']) || !empty($_POST['name'])) ?
            Categories::add($_POST['name'] ?? '', $_POST['slug'] ?? '') : [] ;

        //reste $_post variable
        if($addCatProcess === true){
            $_POST["name"] = null ;
            $_POST["slug"] = null ;
        }

        $errors = [] ;
        $errors = is_array($addCatProcess) ? array_merge($errors, $addCatProcess) : [];
        $errors = is_array($removeCatProcess) ? array_merge($errors, $removeCatProcess) : [];

        // Render the view with the response
        MainController::render('gestions.categories', [
            "title" => "Ajouter ou supprimer une catégorie | Daily Movies ",
            "categories" => Categories::getAll(),
            "response" => [
                "erreurs" => (object)$errors,
                "process" => (object)[
                    'add' => is_bool($addCatProcess) && $addCatProcess,
                    'remove' => is_bool($removeCatProcess) &&  $removeCatProcess ,
                ]
            ]
        ]);;


    }
}
