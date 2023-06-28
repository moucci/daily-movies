<?php

require_once 'addarticle.php';
require_once 'addimage.php';
require_once 'addcategorie.php';

use classes\Db;

$db = Db::getDb();


$query = "SELECT * FROM `articles`";

$req = $db->query($query);

$articles = $req->fetchAll(PDO::FETCH_ASSOC);



//on récupère les catégories
$query = "SELECT * FROM `categories`";

$req = $db->query($query);

$categories = $req->fetchAll(PDO::FETCH_ASSOC);



//ajout d'un article
$erreurs = [];


if (!empty($_POST)) :

    //on vérifie que le titre n'est pas vide
    if (empty($_POST['title'])) {
        $erreurs['title'] = 'Le champ titre est obligatoire.';
    }

    //on vérifie que le contenu n'est pas vide
    if (empty($_POST['content'])) {
        $erreurs['content'] = 'Le champ contenu est obligatoire.';
    }



    //on traite l'image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];

        addimage($image);
    } else {
        $erreurs['image'] = 'Le champ image est obligatoire.';
    }


    // on vérifie si la catégorie est vide

    if (empty($_POST['categories'])) {
        $erreurs['categories'] = 'Le champ catégorie est obligatoire.';
    }


    if (isset($_POST['title']) && empty($erreurs)) :

        //à effacer
        $_POST['user_id'] = 2;
        //à effacer


        $user_id = $_POST['user_id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $nomimage = $_SESSION['nomimage'];


        addarticle($db, $user_id, $title, $content, $nomimage);

        foreach ($_POST['categories'] as $categorie) {
            addcategorie($db, $user_id, $title, $content, $nomimage, intval($categorie));
        }



    endif;
endif;

?>


<section>
    <?php if (isset($_GET['new'])) : ?>

        <!-- formulaire de création d'un article -->
        <form class="border rounded p-2 position-relative m-auto mt-5" style="max-width: 80vw;" action="gestions.php" method="POST" enctype="multipart/form-data">

            <div class="d-flex flex-column">


                <?php if (isset($erreurs['title'])) : ?>
                    <span class="text-danger"><?= $erreurs['title'] ?></span>
                <?php endif; ?>
                <!-- title de l'article -->
                <input type="text" placeholder="Titre" name="title" class="mt-2 rounded" style="max-width: 500px;">


                <?php if (isset($erreurs['content'])) : ?>
                    <span class="text-danger"><?= $erreurs['content'] ?></span>
                <?php endif; ?>
                <!-- content de l'article -->
                <textarea type="text" placeholder="Contenu" name="content" class="mt-2 text-break overflow-visible rounded" style="height: 200px;"></textarea>


                <?php if (isset($erreurs['categories'])) : ?>
                    <span class="text-danger"><?= $erreurs['categories'] ?></span>
                <?php endif; ?>
                <!-- les catégories -->
                <div>
                    <label for="categories">Ajouter une catégorie (pour choisir plusieurs catégories maintenez 'ctrl' au moment du click)</label>

                    <select name="categories[]" id="categories" class="text-center" multiple size="2">
                        
                        <?php foreach ($categories as $categorie) : ?>

                            <option value="<?= $categorie['id'] ?>"><?= $categorie['name'] ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>


                <div>
                    <?php if (isset($erreurs['image'])) : ?>
                        <span class="text-danger"><?= $erreurs['image'] ?></span>
                    <?php endif; ?>
                    <!-- l'image de l'article -->
                    <input type="file" name="image" id="image" class="mt-3">


                    <?php if (isset($erreurs['autre'])) : ?>
                        <span class="text-danger"><?= $erreurs['autre'] ?></span>
                    <?php endif; ?>

                    <button class="mt-2 text-center border border-secondary rounded-pill">
                        <p class="mb-0 p-2">Créer un article
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </p>
                    </button>
                </div>

            </div>

        </form>

    <?php else : ?>

        <button class="mt-2 text-center border border-secondary rounded-pill">
            <a href="gestions?new" class="mb-0 p-2">Créer un article
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                </svg>
            </a>
        </button>




        <main class="d-flex flex-wrap-reverse flex-row-reverse justify-content-center mt-5">
            <?php
            foreach ($articles as $article) :
            ?>

                <article class="card mb-5 ms-5" style="width: 18rem;">
                    <a href="article.php?id=<?= $article['id'] ?>"><img src="../public/assets/images/full/<?= $article['image'] ?>" alt="" class="card-img-top"></a>
                    <div class="card-body">
                        <h3 class="card-title fst-italic text-capitalize"><a href="article.php?id=<?= $article['id'] ?>"><?= $article['title'] . ' :' ?></a></h3>
                        <p class="card-text text-wrap">
                            <?= substr($article['content'], 0, 200) . '...'; ?>
                        </p>
                    </div>
                    <div class="d-flex justify-content-evenly align-items-baseline">
                        <p class="fw-bold">
                            <?php

                            $user_id = $article['user_id'];

                            $query = "SELECT * FROM `users` WHERE `id` = $user_id";


                            $req = $db->query($query);


                            $user = $req->fetch(PDO::FETCH_ASSOC);

                            echo $user['nom'] . ' ' . $user['prenom'];

                            ?>
                        </p>

                        <a href="gestion.php?edit=<?= $article['id'] ?>" class="btn btn-primary">Editer</a>


                    </div>
                </article>

            <?php
            endforeach;
            ?>

        </main>

    <?php endif; ?>

</section>