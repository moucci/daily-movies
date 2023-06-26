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

    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

    $erreurs = [];


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
        $query = "SELECT * FROM `users` WHERE `email` = :email";


        $req = $db->prepare($query);

        $req->bindParam(':email', $email, PDO::PARAM_STR);

        
        try {

            $req->execute();

        } catch (PDOException $error) {

            $erreurs['autre'] = 'Une erreur est survenue, veuillez réessayer ultérieurement.';

        }
        
        $info_user = $req->fetch(PDO::FETCH_ASSOC);

        if(password_verify($mdp, $info_user['mdp'])){
            $_SESSION['connected'] = 'true';
            // headers('location: ');
        } else{
            $erreurs['autre'] = 'Email ou Mot de passe incorrect.';
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

    <form action="signin.php" method="POST" class="d-flex flex-column justify-content-evenly align-items-center">

        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['email'])) {
                                                echo $erreurs['email'];
                                            } ?></span>
        <input type="email" name="email" id="email" placeholder="Email" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['mdp'])) {
                                                echo $erreurs['mdp'];
                                            } ?></span>
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?php if (isset($erreurs['autre'])) {
                                                echo $erreurs['autre'];
                                            } ?></span>

        <button class="btn btn-secondary mt-3">Se connecter</button>
    </form>


</body>

</html>