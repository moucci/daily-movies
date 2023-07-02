<?php

namespace classes\controllers;

use classes\Config;
use classes\Core;
use classes\Db;
use classes\Routes;
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
     * @param $idArticle
     * @return array|void
     */
    public static function updateArticle(array $data, int $idArticle)
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
        $content = trim($content);
        $listCategories = $data['categories'] ?? [];

        //check data
        $erreurs = self::checkDataArticle($title, $slug, $content, Categories::getAll(), $listCategories, false );

        echo "traitement ";

        die;
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
        $title = trim($title);
        $slug = $data['slug'] ?? '';
        $slug = trim($slug);
        $content = $data['content'] ?? '';
        $content = trim($content);
        $listCategories = $data['categories'] ?? [];

        //check data
        $checkSlug = ($action === 'UPDATE') ? false : true;
        $erreurs = self::checkDataArticle($title, $slug, $content, $categories, $listCategories, $checkSlug);

        //check image
        $checkImg = self::checkImage('image', 'FULL');
        if ($checkImg !== true) $erreurs['file'] = $checkImg;

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

        //insert list of car
        try {
            $qInsertCat = "INSERT INTO article_categories (article_id, categorie_id) VALUES (:article_id, :categorie_id)";
            //start transaction
            $db->beginTransaction();
            $req = $db->prepare($qInsertCat);
            // bind value and execute query
            foreach ($listCategories as $idCat) {
                $req->bindParam(':article_id', $idArticle, PDO::PARAM_INT);
                $req->bindParam(':categorie_id', $idCat, PDO::PARAM_INT);
                $req->execute();
            }
            // end transaction
            $db->commit();
            return [
                'process' => true,
                'message' => 'L\'article a bien été créé.'
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
     * Methode to check data without image for article
     * @param string $title
     * @param string $content
     * @param array $categories
     * @return array
     */
    private static function checkDataArticle(string $title, string $slug, string $content, array $categories, array $listCats, bool $checkSlug = true): array
    {
        //tableau d'erreur
        $erreurs = [];
        //on vérifie que le titre n'est pas vide
        if (empty($title) || strlen($title) < 10)
            $erreurs['title'] = 'Le champ titre est obligatoire: minimum de 50 characters ';

        //on vérifie que le slug  n'est pas vide
        if (empty($slug) || strlen($slug) < 10)
            $erreurs['slug'] = 'Le champ slug est obligatoire: minimum de 50 characters ';

        //if user ask to check slug
        if ($checkSlug) {
            $check = self::checkSlugExist($slug);
            //if error
            if (is_string($check)) $erreurs['autre'] = $check;
            // if slug existe
            if ($check === true) $erreurs["slug"] = "le slug existe déja";
        }

        //on vérifie que le contenu n'est pas vide
        if (empty($content) || strlen($content) < 200)
            $erreurs['content'] = 'Le champ contenu est obligatoire.';

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
     * @return bool|string true if exist , false if no , error message if sql query wrong
     */
    private static function checkSlugExist(string $slug): bool|string
    {
        $db = Db::getDb();
        $query = "SELECT slug FROM articles WHERE slug = :slug LIMIT 1";
        $req = $db->prepare($query);
        $slug = strtolower($slug);
        $req->bindParam(":slug", $slug);
        if (!$req->execute()) return "une erreur est survenue lors de la vérification du slug ";
        return !($req->rowCount() <= 0);
    }

}