<?php

namespace classes\controllers;

use classes\Core;
use classes\Db;
use PDO;

class Articles extends MainController
{

    /**
     * Methode to return all categorie on table caterogies
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
//        if(!$req->execute()){
//            return [
//                "erreur" => $req->errorInfo() ,
//            ];
//        }
//
//        $categories = $req->fetchAll(PDO::FETCH_OBJ);
//        $totalResults = $req->rowCount();
//
//        $totalRowsResult = $db->query("SELECT FOUND_ROWS()");
////        $totalRows = $totalRowsResult->fetch_row()[0];
//        $categories['totalRows'] = $totalRowsResult->fetch(PDO::FETCH_OBJ);
//
//
//
//
//
//        $req->closeCursor();
//        return $totalResults == 0 ? [] : $categories;
    }


}