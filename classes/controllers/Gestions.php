<?php

namespace classes\controllers;

use classes\Core;
use classes\Routes;
use PDO;
use PDOException;

class Gestions extends MainController
{
    public function __construct()
    {

        // Core::var_dump_pre(Routes::getParams()) ;
        Routes::getParams();
        MainController::render("gestions");
    }


    /**
     * $db objest 
     * user_id string -> l'id user
     * $title string  -> le titre
     * $content string -> le contenu
     * $image string -> le nom de l'image
     */
    public static function addarticle(object $db, int $user_id, string $title, string $content, string $image)
    {
        $query = "INSERT INTO `articles` (user_id, title, content, image, slug) VALUES (:user_id, :title, :content, :image, :slug)";

        $req = $db->prepare($query);


        $req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $req->bindParam(':title', $title, PDO::PARAM_STR);
        $req->bindParam(':content', $content, PDO::PARAM_STR);
        $req->bindParam(':image', $image, PDO::PARAM_STR);

        $slug = Gestions::slugify($title);
        $req->bindParam(':slug', $slug, PDO::PARAM_STR);

        try {
            $req->execute();
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
     * création d'une image 16:9
     */
    public static function addimagefull()
    {

        $image = $_FILES['image'];



        // on vérifie l'extension et le type Mime
        $allowed = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        $filename = $image['name'];
        $filetype = $image['type'];
        $filesize = $image['size'];





        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
            //$erreurs['image'] = 'Format de fichier incorrect.';
            return [
                "process" => false,

                "message_erreur" => "Format de fichier incorrect.",
            ];
        }

        //on limite à 1 Mio
        if ($filesize > 1024 * 1024) {
        }



        $newfilename = $image['tmp_name'];
        //on génère un nom unique
        $newname = md5(uniqid());

        $newname2 = "$newname.$extension";

        chmod($newfilename, 0644);

        $dimension = getimagesize($newfilename);

        $largeur = $dimension[0];
        $hauteur = $dimension[1];


        switch ($dimension['mime']) {
            case 'image/png':
                $imagesource = imagecreatefrompng($newfilename);
                break;

            case 'image/jpeg':
                $imagesource = imagecreatefromjpeg($newfilename);
                break;

            default:
                return [
                    "process" => false,

                    "message_erreur" => "un truc qui va pas",
                ];
                break;
        }


        $fullimage = imagecreatetruecolor($largeur, intval(9 * 100 / 16 * $largeur / 100));
        $taille = intval(((100 - (($hauteur * 100) / $largeur)) / 2) * $largeur / 100);

        imagecopyresampled(
            $fullimage,
            $imagesource,
            0,
            0,
            0,
            $taille,
            $largeur,
            $hauteur,
            $largeur,
            $hauteur,
        );


        switch ($dimension['mime']) {
            case 'image/png':
                imagepng($fullimage, "./public/assets/images/full/$newname.$extension");
                break;

            case 'image/jpeg':
                imagejpeg($fullimage, "./public/assets/images/full/$newname.$extension");

                break;
        }

        imagedestroy($imagesource);
        imagedestroy($fullimage);


        return [
            "process" => true,

            "name_img" => $newname2,
        ];
    }

    /**
     * création d'une image carré
     * $name le nom généré avec unique id dans la fonction addimagefull
     */
    public static function addimagesquare($name)
    {

        $image = $_FILES['image'];



        // on vérifie l'extension et le type Mime
        $allowed = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        $filename = $image['name'];
        $filetype = $image['type'];
        $filesize = $image['size'];





        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
            //$erreurs['image'] = 'Format de fichier incorrect.';
            return [
                "process" => false,

                "message_erreur" => "Format de fichier incorrect.",
            ];
        }

        //on limite à 1 Mio
        if ($filesize > 1024 * 1024) {
        }




        $newfilename = $image['tmp_name'];



        chmod($newfilename, 0644);

        $dimension = getimagesize($newfilename);

        $largeur = $dimension[0];
        $hauteur = $dimension[1];


        switch ($dimension['mime']) {
            case 'image/png':
                $imagesource = imagecreatefrompng($newfilename);
                break;

            case 'image/jpeg':
                $imagesource = imagecreatefromjpeg($newfilename);
                break;

            default:
                return [
                    "process" => false,

                    "message_erreur" => "un truc qui va pas",
                ];
                break;
        }

        if ($largeur <= $hauteur) {
            $nouvelleimage = imagecreatetruecolor($largeur, $largeur);
            $taille = intval(((100 - (($largeur * 100) / $hauteur)) / 2) * $hauteur / 100);

            imagecopyresampled(
                $nouvelleimage,
                $imagesource,
                0,
                0,
                0,
                $taille,
                $largeur,
                $hauteur,
                $largeur,
                $hauteur,
            );
        }

        if ($largeur > $hauteur) {
            $nouvelleimage = imagecreatetruecolor($hauteur, $hauteur);
            $taille = intval(((100 - (($hauteur * 100) / $largeur)) / 2) * $largeur / 100);

            imagecopyresampled(
                $nouvelleimage,
                $imagesource,
                0,
                0,
                $taille,
                0,
                $largeur,
                $hauteur,
                $largeur,
                $hauteur,
            );
        }


        switch ($dimension['mime']) {
            case 'image/png':
                imagepng($nouvelleimage, "./public/assets/images/square/$name");
                break;

            case 'image/jpeg':
                imagejpeg($nouvelleimage, "./public/assets/images/square/$name");

                break;
        }

        imagedestroy($imagesource);
        imagedestroy($nouvelleimage);


        return [
            "process" => true,

            "name_img" => $name,
        ];
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

    /**
     * $db -> object
     * $img -> la nouvelle image
     * $idarticle -> l'id de l'article 
     */
    public static function modifimg (object $db, string $img, int $idarticle)
    {
        $query = "UPDATE `articles` SET `image` = :img WHERE `id` = :id";

        $req = $db->prepare($query);

        $req->bindParam(':img', $img, PDO::PARAM_STR);
        $req->bindParam(':id', $idarticle, PDO::PARAM_INT);


        try {
            $req->execute();
            return [
                'process' => true,
                'message' => 'L\'image a bien été modifiée.',
            ];
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }
    }


    /**
     * $db -> object
     * $modiftitle -> le nouveau titre
     * $idarticle -> l'id de l'article 
     */
    public static function modiftitle (object $db, string $modiftitle, int $idarticle)
    {
        $query = "UPDATE `articles` SET `title` = :title WHERE `id` = :id";

        $req = $db->prepare($query);

        $req->bindParam(':title', $modiftitle, PDO::PARAM_STR);
        $req->bindParam(':id', $idarticle, PDO::PARAM_INT);


        try {
            $req->execute();
            return [
                'process' => true,
                'message' => 'Le titre a bien été modifié.',
            ];
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }
    }


    /**
     * $db -> object
     * $modifcontent -> le nouveau titre
     * $idarticle -> l'id de l'article 
     */
    public static function modifcontent (object $db, string $modifcontent, int $idarticle)
    {
        $query = "UPDATE `articles` SET `content` = :content WHERE `id` = :id";

        $req = $db->prepare($query);

        $req->bindParam(':content', $modifcontent, PDO::PARAM_STR);
        $req->bindParam(':id', $idarticle, PDO::PARAM_INT);


        try {
            $req->execute();
            return [
                'process' => true,
                'message' => 'Le contenu a bien été modifié.',
            ];
        } catch (PDOException $error) {
            return [
                'process' => false,
                'message' => 'Une erreur est survenue, veuillez réessayer ultérieurement.',
            ];
        }
    }
}
