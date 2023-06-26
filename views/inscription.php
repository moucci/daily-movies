<?php
session_start();

const DBHOST = "localhost";
const DBUSER = "root";
const DBPASS = "";
const DBNAME = "daily-movies";

$dsn = "mysql:dbname=" . DBNAME . ";host=" . DBHOST;

try {
    $db = new PDO($dsn, DBUSER, DBPASS);

    $db->exec("SET NAMES utf8");
} catch (PDOException $e) {
    die($e->getMessage());
}


if (!empty($_POST)) :

    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $erreurs = [];

    // on vérifie le nom
    if (empty($nom)) {
        $erreurs['nom'] = 'Le champ nom est requis.';
    }

    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/', $nom)) {
        $erreurs['nom'] = 'Le champ nom ne doit pas contenir de caractères spéciaux.';
    }


    //on vérifie le prénom
    if (empty($prenom)) {
        $erreurs['prenom'] = 'Le champ prénom est requis.';
    }

    if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/', $prenom)) {
        $erreurs['prenom'] = 'Le champ prénom ne doit pas contenir de caractères spéciaux.';
    }


    //on vérifie l'email
    if (empty($email)) {
        $erreurs['email'] = "Le champ adresse email est requis.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "L'adresse email est invalide.";
    }


    //on vérifie le mot de passe
    if (empty($mdp)) {
        $erreurs['mdp'] = "Le champ mot de passe est requis.";
    }

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@?!"+$*#&_\-^%])[A-Za-z\d@?#"!+$*&_\-^%]{16,}$/', $mdp)) {
        $erreurs['mdp'] = "Le mot de passe est invalide. Il doit contenir au moins 16 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }



    if (empty($erreurs) && !empty($_POST)) {
        $query = "INSERT INTO `users` (nom, prenom, email, mdp) VALUES (:nom, :prenom, :email, :mdp)";

        $req = $db->prepare($query);

        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':email', $email, PDO::PARAM_STR);

        $mdp = password_hash($mdp, PASSWORD_ARGON2ID);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);


        try {
            $req->execute();
            $_SESSION['connected'] = 'true';
            // header();
            // die();
        } catch (PDOException $error) {
            $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';
        }
    }

endif;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Connexion</title>
</head>

<body>

    <form action="signup.php" method="POST" class="d-flex flex-column justify-content-evenly align-items-center">

        <span class="mt-5 mb-2 text-danger"><?php if (isset($erreurs['nom'])) {
                                                echo $erreurs['nom'];
                                            } ?></span>
        <input type="text" name="nom" id="nom" placeholder="Nom" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['prenom'])) {
                                                echo $erreurs['prenom'];
                                            } ?></span>
        <input type="text" name="prenom" id="prenom" placeholder="Prénom" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['email'])) {
                                                echo $erreurs['email'];
                                            } ?></span>
        <input type="email" name="email" id="email" placeholder="Email" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['mdp'])) {
                                                echo $erreurs['mdp'];
                                            } ?></span>
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <p class="mt-2 text-secondary">Le mot de passe doit contenir au moins : </p>
        <ul class="text-secondary">
            <li>Une majsucule</li>
            <li>Une minuscule</li>
            <li>Un chiffre</li>
            <li>Un caractère spécial</li>
        </ul>


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['autre'])) {
                                                echo $erreurs['autre'];
                                            } ?></span>

        <button class="btn btn-secondary mt-3">S'inscrire</button>
    </form>


</body>

</html>