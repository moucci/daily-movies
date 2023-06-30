<?php

namespace classes\controllers;

use classes\Core;
use classes\Db;
use classes\Routes;
use PDO;

class Articles extends MainController
{
    private int|null $idArticle;


    public function __construct()
    {
        //get params in url
        $this->idArticle = Routes::getParams()[1] ?? null;

        //check  params
        if (empty($this->idArticle)) {
            MainController::render("notFound", [
                "title" => "La page que vous avez demandée n'existe pas",
            ]);
        }

        //get data
        $data = self::getById($this->idArticle);

        //check data
        $view = (!empty($data["article"])) ? "article" : "notFound";
        MainController::render($view, [
            "title" => "{$data['article']->title} | Daily Movies",
            "article" => $data["article"]
        ]);
    }


    /**
     * @param int $page number of page
     * @param int $limit limit if items to select
     * @param int $offset offset to start selection
     * @return array
     */
    public static function getAll(int $page = 1, int $limit = 7, int $offset = 0): array
    {
        //GET DB CONNEXION
        $db = Db::getDb();

        //get data from table
        $query = "SELECT SQL_CALC_FOUND_ROWS 
                                    art.id ,  
                                    art.title , 
                                    art.content ,  
                                    art.slug , 
                                    art.image,
                                    (DATE_FORMAT(art.date_creation, '%d %b %Y')) as date_creation ,
                                    usr.nom  ,
                                    usr.prenom,
                                    cat.name 
                                    FROM articles as art
                                    LEFT JOIN users as usr on usr.id = art.user_id
                                    LEFT JOIN article_categories as art_cat on  art_cat.article_id = art.id
                                    LEFT JOIN categories as cat on  cat.id = art_cat.categorie_id
                                    ORDER BY date_creation DESC 
                                    limit :limit offset :offset 
                                    ";
        //prepare query
        $req = $db->prepare($query);

        //bind value
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);

        //get pagination
        $offset = ($page - 1) * $limit;
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);

        //execute
        if (!$req->execute()) {
            return [
                "erreur" => $req->errorInfo(),
            ];
        }
        //fetch data
        $articles = $req->fetchAll(PDO::FETCH_OBJ);

        //get nomber of resulta
        $totalRowsResult = $db->query("SELECT FOUND_ROWS()");
        $totalRows = (int)$totalRowsResult->fetchColumn();

        //generate pagination
        for ($page = 1; $page < ceil($totalRows / $limit); $page++) $pages[] = $page;

        //return results
        return [
            "articles" => ($totalRows == 0) ? [] : $articles,
            "pages" => $pages ?? [],
        ];
    }

    /**
     * methode to get article by id
     * @param int $idArticle
     * @return array
     */
    public static function getById(int $idArticle): array
    {
        //GET DB CONNEXION
        $db = Db::getDb();

        //get data from table
        $query = "SELECT SQL_CALC_FOUND_ROWS 
                                    art.id ,  
                                    art.title , 
                                    art.content ,  
                                    art.slug , 
                                    art.image,
                                    (DATE_FORMAT(art.date_creation, '%d %b %Y')) as date_creation ,
                                    usr.nom  ,
                                    usr.prenom,
                                    GROUP_CONCAT(DISTINCT cat.name) AS cats
                                    FROM articles as art
                                    LEFT JOIN users as usr on usr.id = art.user_id
                                    LEFT JOIN article_categories as art_cat on  art_cat.article_id = art.id
                                    LEFT JOIN categories as cat on  cat.id = art_cat.categorie_id
                                    WHERE art.id = :idArticle 
                                    limit 1 
                                    ";
        //prepare query
        $req = $db->prepare($query);
        $req->bindParam(':idArticle', $idArticle, PDO::PARAM_INT);

        //execute
        if (!$req->execute()) {
            return [
                "erreur" => $req->errorInfo(),
            ];
        }
        //fetch data
        $articles = $req->fetch(PDO::FETCH_OBJ);

        //format cats
        if (isset($articles->cats)) $articles->cats = explode(',', $articles->cats);

        //return results
        return [
            "article" => ($req->rowCount() === 0) ? [] : $articles,
        ];

    }



    /**
     * methode to get article by categorie
     * @param string $categorie
     * @param int $page
     * @param int $limit
     * @param int $offset
     * @return array|array[]
     */
    public static function getAllByCategorie(string $categorie ,int $page = 1, int $limit = 6, int $offset = 0): array
    {
        //GET DB CONNEXION
        $db = Db::getDb();

        //get data from table
        $query = "SELECT SQL_CALC_FOUND_ROWS
                    art.id,
                    art.title,
                    art.content,
                    art.slug,
                    art.image,
                    DATE_FORMAT(art.date_creation, '%d %b %Y') AS date_creation,
                    usr.nom,
                    usr.prenom,
                    GROUP_CONCAT( cat.name) AS categories
                    FROM articles AS art
                    LEFT JOIN users AS usr ON usr.id = art.user_id
                    LEFT JOIN article_categories AS art_cat ON art_cat.article_id = art.id
                    LEFT JOIN categories AS cat ON cat.id = art_cat.categorie_id
                    WHERE cat.name = :categorie 
                    GROUP BY art.id limit :limit offset :offset ;
                    ";
        //prepare query
        $req = $db->prepare($query);
        $req->bindParam(':categorie', $categorie , PDO::PARAM_STR );

        //bind value
        $req->bindParam(':limit', $limit, PDO::PARAM_INT);

        //get pagination
        $offset = ( $page - 1 ) * $limit;
        $req->bindParam(':offset', $offset, PDO::PARAM_INT);

        //execute
        if (!$req->execute()) {
            return [
                "erreur" => $req->errorInfo(),
            ];
        }

        //fetch data
        $articles = $req->fetchAll(PDO::FETCH_OBJ);

        //format cats
        if (isset($articles->cats)) $articles->cats = explode(',', $articles->cats);

        //get nomber of resulta
        $totalRowsResult = $db->query("SELECT FOUND_ROWS()");
        $totalRows = (int)$totalRowsResult->fetchColumn();

        //generate pagination
        for ($page = 1; $page < ceil($totalRows / $limit); $page++) $pages[] = $page;

        //return results
        return [
            "articles" => ($req->rowCount() === 0) ? [] : $articles,
            "pages" => $pages ?? [],

        ];

    }





}