<?php

namespace classes\controllers;

use classes\Config;
use classes\Core;
use classes\Db;
use classes\Routes;
use Exception;
use PDO;
use function Sodium\add;

class Articles extends MainController
{
    /** Id Article
     * @var int|mixed|null
     */
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
     * Methode return all article
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
                        art.id,
                        art.title,
                        art.content,
                        art.slug,
                        art.image,
                        (DATE_FORMAT(art.date_creation, '%d %b %Y')) AS date_creation,
                        usr.nom,
                        usr.prenom,
                        GROUP_CONCAT(cat.name) AS categories
                    FROM articles AS art
                    LEFT JOIN users AS usr ON usr.id = art.user_id
                    LEFT JOIN article_categories AS art_cat ON art_cat.article_id = art.id
                    LEFT JOIN categories AS cat ON cat.id = art_cat.categorie_id
                    GROUP BY art.id, art.title, art.content, art.slug, art.image, date_creation, usr.nom, usr.prenom
                    ORDER BY art.date_creation DESC
                    LIMIT :limit OFFSET :offset;
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
        for ($page = 0; $page < ceil($totalRows / $limit); $page++) $pages[] = $page + 1;

        //return results
        return [
            "articles" => ($totalRows == 0) ? [] : $articles,
            "pages" => $pages ?? [],
        ];
    }

    /**
     * Methode to get article by id
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
                                    GROUP_CONCAT(DISTINCT cat.name) AS cats,
                                    GROUP_CONCAT(DISTINCT cat.id) AS idCats
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
     * Methode to get article by categorie
     * @param string $categorie
     * @param int $page
     * @param int $limit
     * @param int $offset
     * @return array|array[]
     */
    public static function getAllByCategorie(string $categorie, int $page = 1, int $limit = 6, int $offset = 0): array
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
        $req->bindParam(':categorie', $categorie, PDO::PARAM_STR);

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

    /**
     * Methode to update article
     * @param array $data new data
     * @param array $oldData old data
     * @return array|void
     */
    public static function updateArticle(array $data, array $oldData)
    {

        //if no data
        if (empty($data)) return [
            "process" => true,
            "message" => "EMPTY",
        ];

        //try to get data
        $title = $data['title'] ?? '';
        $title = trim($title);
        $slug = $data['slug'] ?? '';
        $slug = trim($slug);
        $content = $data['content'] ?? '';
        $content = htmlspecialchars(trim($content));
        $listCategories = $data['categories'] ?? [];

        //check data
        $erreurs = self::checkDataArticle($title, $slug, $content, Categories::getAll(), $listCategories);
        $checkSlug = self::checkSlugExist($slug);

        //check slug
        if (isset($checkSlug["id"]) && $checkSlug["id"] !== $oldData["id"]) $erreurs['slug'] = "ce slug existe déja pour un autre article";

        //check image
        if (!empty($_FILES['image']["name"])) {
            //check image
            $checkImg = self::checkImage('image', 'FULL');
            if ($checkImg !== true) $erreurs['file'] = $checkImg;
        }

        //check error
        if (!empty($erreurs)) return [
            "process" => false,
            "message" => "ERROR",
            "erreurs" => (object )$erreurs,
        ];

        if (!empty($_FILES['image']["name"])) {
            // Save the Full image
            $saveFullImage = self::saveImage('image', Config::PATCH_IMG_FULL, 'FULL');
            $fullImageName = $saveFullImage['fileName'];

            // Save the SQUARE image if the full image was successfully saved
            $saveSquareImage = isset($saveFullImage["error"]) ? $saveFullImage : self::saveImage('image', Config::PATCH_IMG_SQUARE, 'SQUARE', $fullImageName);

            // Check if error is set
            if (isset($saveFullImage["error"]) || isset($saveSquareImage["error"])) return [
                "process" => false,
                "message" => "ERROR",
                "erreurs" => (object)[
                    "autre" => $saveSquareImage['error'] ?? $saveFullImage['error']
                ],
            ];

            //delete old image
            try {
                unlink('.' . Config::PATCH_IMG_FULL . $data["file"]);
                unlink('.' . Config::PATCH_IMG_SQUARE . $data["file"]);
            } catch (Exception $exception) {
                //log errir on  file error php
                error_log('unilik impossible' . __DIR__ . Config::PATCH_IMG_FULL . $data["file"]);
            }

        } else {
            $fullImageName = $oldData["image"];
        }

        //insert data on db if all is OK
        $db = Db::getDb();
        $query = "UPDATE `articles` set  
                      title = :title,
                      content= :content,
                      image = :image,
                      slug = :slug 
                        WHERE id = :idArticle";
        $req = $db->prepare($query);
        $req->bindParam(':title', $title, PDO::PARAM_STR);
        $req->bindParam(':content', $content, PDO::PARAM_STR);
        $req->bindParam(':image', $fullImageName, PDO::PARAM_STR);
        $req->bindParam(':slug', $slug, PDO::PARAM_STR);
        $req->bindParam(':idArticle', $oldData['id'], PDO::PARAM_STR);

        //Insert article
        if (!$req->execute()) return [
            'process' => false,
            'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
        ];

        $oldCats = explode(',', $oldData["idCats"]) ?? [];

        //List of cats to add
        $catsToAdd = array_diff($listCategories, $oldCats);

        //liste of cats to  remove
        $catsToRemove = array_diff($oldCats, $listCategories);

        //set new setArticleCategories
        $responseSetCat = Categories::setArticleCategories($catsToAdd, $oldData['id']);

        $responseUnSetCat = (!$responseSetCat) ? $responseSetCat : Categories::unSetArticleCategories($catsToRemove, $oldData['id']);

        if (!$responseUnSetCat) return [
            'process' => false,
            'message' => $responseUnSetCat,
        ];

        return [
            'process' => true,
            'message' => $responseUnSetCat,
        ];

    }

    /**
     * Methoode to add article
     * @param array $data [title, slug , content ,categories] and super Globale $_FILES['image']
     * @return array
     */
    public static function addArticle(array $data, string $action = 'ADD'): array
    {

        //get all categotries
        $categories = Categories::getAll();

        //if no data
        if (empty($data)) return [
            "process" => false,
            "message" => "EMPTY",
            "categories" => $categories
        ];

        //try to get data
        $title = $data['title'] ?? '';
        $title = strtolower(trim($title));
        $slug = $data['slug'] ?? '';
        $slug = strtolower(trim($slug));
        $content = $data['content'] ?? '';
        $content = trim($content);
        $listCategories = $data['categories'] ?? [];

        //check data
        $checkSlug = !(($action === 'UPDATE'));
        $erreurs = Articles::checkDataArticle($title, $slug, $content, $categories, $listCategories);

        //check slug
        $checkSlug = Articles::checkSlugExist($slug);
        if (isset($checkSlug["id"])) $erreurs['slug'] = "ce slug existe déja pour un autre article";

        //check image
        $checkImg = Core::checkImage('image', 'FULL');
        if ($checkImg !== true) $erreurs['file'] = $checkImg;
        //if errors
        if (!empty($erreurs)) return [
            "process" => false,
            "message" => "ERROR",
            "erreurs" => (object)$erreurs,
            "categories" => $categories
        ];

        // Save the Full image
        $saveFullImage = self::saveImage('image', Config::PATCH_IMG_FULL, 'FULL');
        $fullImageName = $saveFullImage['fileName'];

        // Save the SQUARE image if the full image was successfully saved
        $saveSquareImage = isset($saveFullImage["error"]) ? $saveFullImage : self::saveImage('image', Config::PATCH_IMG_SQUARE, 'SQUARE', $fullImageName);

        // Check if error is set
        if (isset($saveFullImage["error"]) || isset($saveSquareImage["error"])) return [
            "process" => false,
            "message" => "ERROR",
            "erreurs" => (object)[
                "autre" => $saveSquareImage['error'] ?? $saveFullImage['error']
            ],
            "categories" => $categories
        ];

        //insert data on db if all is OK
        $db = Db::getDb();
        $query = "INSERT INTO `articles` (user_id, title, content, image, slug) VALUES (:user_id, :title, :content, :image, :slug)";
        $req = $db->prepare($query);
        $req->bindParam(':user_id', $_SESSION["id"], PDO::PARAM_INT);
        $req->bindParam(':title', $title, PDO::PARAM_STR);
        $req->bindParam(':content', $content, PDO::PARAM_STR);
        $req->bindParam(':image', $fullImageName, PDO::PARAM_STR);
        $req->bindParam(':slug', $slug, PDO::PARAM_STR);

        //Insert article
        if (!$req->execute()) return [
            'process' => false,
            'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
        ];

        //get lats id
        $idArticle = $db->lastInsertId();
        $req->closeCursor();

        //set new setArticleCategories
        $responseSetCat = Categories::setArticleCategories($listCategories, $idArticle);
        return !$responseSetCat ? [
            'process' => false,
            'message' => 'Impossible de ajouter les categories, veuillez réessayer ultérieurement.',
        ] : [
            'process' => true,
        ];

    }

    /**
     * Methode to check data without image for article
     * @param string $title
     * @param string $content
     * @param array $categories
     * @return array
     */
    private static function checkDataArticle(string $title, string $slug, string $content, array $categories, array $listCats): array
    {
        //tableau d'erreur
        $erreurs = [];
        //on vérifie que le titre n'est pas vide
        if (empty($title) || strlen($title) < 10)
            $erreurs['title'] = 'Le champ titre est obligatoire: minimum de 10 characters ';

        //on vérifie que le slug  n'est pas vide
        $checkSlug = Core::checkSlug($slug);
        if (!$checkSlug) $erreurs['slug'] = $slug;

        //on vérifie que le contenu n'est pas vide
        if (empty($content) || strlen($content) < 200)
            $erreurs['content'] = 'Le champ contenu est obligatoire minimum 200 characters';

        // on vérifie si la catégorie est vide
        if (empty($listCats)) {
            $erreurs['categories'] = 'Le champ catégorie est obligatoire.';
            return $erreurs;
        };

        // check if cat existe
        $catMatch = 0;
        foreach ($categories as $key => $categorie) {
            if (in_array($categorie->id, $listCats)) {
                $catMatch++;
            }
        }
        if (empty($catMatch))
            $erreurs['categories'] = 'Des catégories sélectionnée ne sont pas encore disponible.';

        //if data no  valide
        return $erreurs;

    }

    /**
     * Methode to check if slug existe on table article
     * @param string $slug
     * @return array|string array if exist , error message if sql query wrong
     */
    private static function checkSlugExist(string $slug): array|string
    {
        $db = Db::getDb();
        $query = "SELECT slug , id FROM articles WHERE slug = :slug LIMIT 1";
        $req = $db->prepare($query);
        $slug = strtolower($slug);
        $req->bindParam(":slug", $slug);
        if (!$req->execute()) return "une erreur est survenue lors de la vérification du slug ";
        return $req->fetch();
    }

}