<?php

/**
 * $db object 
 * $user_id string -> l'id user
 * $title string  -> le titre
 * $content string -> le contenu
 * $image string -> le nom de l'image
 * $idcategorie int -> l'id de la catégorie
 */
function addcategorie(object $db, int $user_id, string $title, string $content, string $image, int $idcategorie)
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
        $message = 'L\'article a bien été créé';
    } catch (PDOException $error) {
        $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
    }

    $articleid = $req->fetch(PDO::FETCH_ASSOC);

    //l'ajout dans article_categories
    $query = "INSERT INTO `article_categories` (article_id, categorie_id) VALUES (:articleid, :idcategorie)";

    $req = $db->prepare($query);

    
    $req->bindParam(':articleid', $articleid['id'], PDO::PARAM_INT);
    $req->bindParam(':idcategorie', $idcategorie, PDO::PARAM_INT);

    try {
        $req->execute();
        $message = 'L\'article a bien été créé';
    } catch (PDOException $error) {
        $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
    }

}
