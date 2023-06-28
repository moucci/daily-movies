<?php
/**
 * $db objest 
 * $name le nom de la catégorie que l'on veut créer
 */
function createcategorie (object $db, string $name) {

    $query = "INSERT INTO `categories` (name, slug) VALUES (:name, :slug)";

    $req = $db->prepare($query);

    $req->bindParam(':name', $name, PDO::PARAM_STR);

    //on fait le slug (je sais pas le faire donc pour l'instant je le laisse comme ça)
    $slug = $name;
    $req->bindParam(':slug', $slug, PDO::PARAM_STR);

    try {
        $req->execute();
        $message = 'L\'article a bien été créé';
    } catch (PDOException $error) {
        $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
    }

}