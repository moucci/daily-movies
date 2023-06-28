<?php

/**
 * $db objest 
 * user_id string -> l'id user
 * $title string  -> le titre
 * $content string -> le contenu
 * $image string -> le nom de l'image
 */
function addarticle(object $db, int $user_id, string $title, string $content, string $image){
    $query = "INSERT INTO `articles` (user_id, title, content, image, slug) VALUES (:user_id, :title, :content, :image, :slug)";

    $req = $db->prepare($query);

    
    $req->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $req->bindParam(':title', $title, PDO::PARAM_STR);
    $req->bindParam(':content', $content, PDO::PARAM_STR);
    $req->bindParam(':image', $image, PDO::PARAM_STR);

    //on fait le slug (je sais pas le faire donc pour l'instant je le laisse comme ça)
    $slug = $title;
    $req->bindParam(':slug', $slug, PDO::PARAM_STR);

    try {
        $req->execute();
        $message = 'L\'article a bien été créé';
    } catch (PDOException $error) {
        $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
    }
}