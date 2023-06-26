<?php
session_start();
// à effacer
$_SESSION['connected'] = 'true';
//à effacer

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

$query = "SELECT * FROM `articles`";


$req = $db->query($query);


$articles = $req->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>

    <div class="position-relative me-5 mt-5 text-end">
        <a href="#" class="me-5 p-2 border border-secondary rounded-pill">Créer un article 
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
</svg>
        </a>
    </div>

    <main class="d-flex flex-wrap justify-content-center mt-5">
        <?php
        foreach ($articles as $article) :
        ?>

            <article class="card mb-5 ms-5" style="width: 18rem;">
                <a href="#"><img src="./assets/images/full/<?= $article['image'] ?>" alt="" class="card-img-top"></a>
                <div class="card-body">
                    <h3 class="card-title fst-italic text-capitalize"><a href="#"><?= $article['title'].' :' ?></a></h3>
                    <p class="card-text text-wrap">
                        <?= substr($article['content'], 0, 200).'...'; ?>
                    </p>
                </div>
                <div class="d-flex justify-content-evenly align-items-baseline">
                    <p class="fw-semibold"><?php

                        $user_id = $article['user_id'];

                        $query = "SELECT * FROM `users` WHERE `id` = $user_id";


                        $req = $db->query($query);


                        $user = $req->fetch(PDO::FETCH_ASSOC);

                        echo $user['nom'] . ' ' . $user['prenom'];

                        ?></p>

                    <?php if (isset($_SESSION['connected']) && $_SESSION['connected'] == 'true'): ?>
                       <a href="#" class="btn btn-primary">Editer</a>
                    <?php endif; ?>

                </div>
            </article>

        <?php
        endforeach;
        ?>

    </main>

</body>

</html>