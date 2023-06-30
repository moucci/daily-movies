<?php

use classes\Db;
use classes\controllers\Gestions;
use classes\Routes;
use classes\controllers\Articles;
use classes\controllers\Categories;

$db = Db::getDb();

$categories = Categories::getAll();

$articles = Articles::getAll();
$articles = $articles['articles'];

if (isset(Routes::getParams()[2])) {
    $edit = Articles::getById(Routes::getParams()[2]);
    $edit = $edit['article'];
    // echo '<pre>';
    // var_dump($edit);
    // echo '</pre>';
}



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







    // on vérifie si la catégorie est vide

    if (empty($_POST['categories'])) {
        $erreurs['categories'] = 'Le champ catégorie est obligatoire.';
    }



    //on traite l'image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image'];

        if (empty($erreurs)) {
            $reponseimage = Gestions::addimagefull();
            Gestions::addimagesquare($reponseimage["name_img"]);
        }
    } else {
        $reponseimage['message_erreur'] = 'Le champ image est obligatoire.';
    }



    if (isset($_POST['title']) && empty($erreurs) && $reponseimage['process'] == true) :


        $user_id = $_SESSION['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $nomimage =  $reponseimage["name_img"];


        $messagearticle = Gestions::addarticle($db, $user_id, $title, $content, $nomimage);


        foreach ($_POST['categories'] as $categorie) {
            $messagecategorie = Gestions::addcategorie($db, $user_id, $title, $content, $nomimage, intval($categorie));

            if ($messagecategorie['process'] == false && file_exists((__DIR__ . '../../public/assets/images/full/' . $nomimage))) {
                unlink(__DIR__ . '../../public/assets/images/full/' . $nomimage);
            }

            if ($messagecategorie['process'] == false && file_exists((__DIR__ . '../../public/assets/images/square/' . $nomimage))) {
                unlink(__DIR__ . '../../public/assets/images/square/' . $nomimage);
            }
        }


        if ($messagearticle['process'] == false && file_exists((__DIR__ . '../../public/assets/images/full/' . $nomimage))) {
            unlink(__DIR__ . '../../public/assets/images/full/' . $nomimage);
        }

        if ($messagearticle['process'] == false && file_exists((__DIR__ . '../../public/assets/images/square/' . $nomimage))) {
            unlink(__DIR__ . '../../public/assets/images/square/' . $nomimage);
        }


    endif;


    if (empty($_POST['addcat'])) {
        $erreurs['addcat'] = 'Le champ est obligatoire';
    }

    if (!empty($_POST['addcat'])) {
        $repcat = Gestions::createcategorie($db, $_POST['addcat']);
    }


















































    // la modif d'article


    // //l'image
    // if (!empty($_FILES['image']['name'])) {
    //     $modifimg = Gestions::addimagefull();
    //     Gestions::addimagesquare($modifimg["name_img"]);

    //     if ($modifimg["process"] == true) {

    //         $repmodifimg = Gestions::modifimg($db, $modifimg["name_img"], Routes::getParams()[2]);
    //         var_dump($repmodifimg);
    //     }
    // }


    // //le titre
    // if (!empty($_POST['modiftitle']) &&  $_POST['modiftitle'] != $edit->title) {
    //     $modiftitle = Gestions::modiftitle($db, $_POST['modiftitle'], Routes::getParams()[2]);
    // }


    // //le contenu
    // if (!empty($_POST['modifcontent']) &&  $_POST['modifcontent'] != $edit->content) {
    //     $modifcontent = Gestions::modifcontent($db, $_POST['modifcontent'], Routes::getParams()[2]);
    // }


    //les catégories

    

















    //on rerécupère les infos de l'article
    if (isset(Routes::getParams()[2])) {
        $edit = Articles::getById(Routes::getParams()[2]);
        $edit = $edit['article'];
    }

endif;

?>


<section>


    <?php if (isset(Routes::getParams()[1]) && Routes::getParams()[1] == 'new') : ?>

        <!-- formulaire de création d'un article -->
        <form class="border rounded p-2 position-relative m-auto mt-5" style="max-width: 80vw;" method="POST" enctype="multipart/form-data">

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

                            <option value="<?= $categorie->id ?>"><?= $categorie->name ?></option>

                        <?php endforeach; ?>

                    </select>

                    <a href="categorie">Ajouter une catégorie</a>

                </div>


                <div>
                    <?php if (isset($reponseimage['message_erreur'])) : ?>
                        <span class="text-danger"><?= $reponseimage['message_erreur'] ?></span>
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

        <a href="/gestions">Revenir à la page gestion</a>








    <?php elseif (isset(Routes::getParams()[1]) && Routes::getParams()[1] == 'categorie') : ?>

        <form class="border rounded p-2 position-relative m-auto mt-5 d-flex flex-column text-center" style="max-width: 30vw;" method="POST">

            <?php if (isset($erreurs['addcat'])) : ?>
                <span class="text-danger"><?= $erreurs['addcat'] ?></span>
            <?php endif; ?>

            <input type="text" placeholder="Nom de la catégorie" name="addcat" class="mt-3 mb-3 text-center">

            <?php if (isset($repcat['message'])) : ?>
                <span class="text-danger"><?= $repcat['message'] ?></span>
            <?php endif; ?>

            <button>Créer la catégorie</button>

        </form>


        <a href="new" class="m-auto mt-5">Revenir à la création d'article</a>










































    <?php elseif (isset(Routes::getParams()[1]) && Routes::getParams()[1] == 'edit' && !empty(Routes::getParams()[2])) : ?>


        <form class="border rounded p-2 position-relative m-auto mt-5" style="max-width: 60vw;" method="POST" enctype="multipart/form-data">

            <div class="d-flex flex-column">

                <img src="../../public/assets/images/full/<?= $edit->image ?>" alt="" class="card-img-top">
                <!-- l'image -->
                <div>
                    <label for="image">Changer l'image</label>
                    <input type="file" name="image" id="image" value="<?= $edit->image ?>">
                </div>

                <!-- le titre -->
                <input type="text" name="modiftitle" value="<?= $edit->title ?>" class="mt-2 rounded" style="max-width: 500px;">

                <!-- le contenu -->
                <textarea type="text" name="modifcontent" class="mt-2 text-break overflow-visible rounded" style="height: 200px;"><?= $edit->content ?></textarea>

                <!-- les catégories -->
                <div>
                    <label for="categories">Modifier les catégories (pour choisir plusieurs catégories maintenez 'ctrl' au moment du click)</label>

                    <select name="modifcategories[]" id="categories" class="text-center" multiple size="2">

                        <?php foreach ($categories as $categorie) : ?>

                            <option value="<?= $categorie->id ?>,<?= $categorie->name ?>" <?php if (!empty($edit->cats)) {
                                                                                                foreach ($edit->cats as $cat) {
                                                                                                    if ($categorie->name == $cat) {
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            ?>>
                                <?= $categorie->name ?></option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <button>Modifier l'article</button>
            </div>
        </form>
        <?php
        ?>













    <?php else : ?>

        <button class="mt-2 text-center border border-secondary rounded-pill">
            <a href="gestions/new" class="mb-0 p-2">Créer un article
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
                    <img src="../public/assets/images/full/<?= $article->image ?>" alt="" class="card-img-top">
                    <div class="card-body">
                        <h3 class="card-title fst-italic text-capitalize"><?= $article->title . ' :' ?></h3>
                        <p class="card-text text-wrap">
                            <?= substr($article->content, 0, 200) . '...'; ?>
                        </p>
                    </div>
                    <div class="d-flex justify-content-evenly align-items-baseline">
                        <p class="fw-bold">
                            <?php
                            echo $article->nom . ' ' . $article->prenom;

                            ?>
                        </p>

                        <a href="gestions/edit/<?= $article->id ?>" class="btn btn-primary">Editer</a>


                    </div>
                </article>

            <?php
            endforeach;
            ?>

        </main>

    <?php endif; ?>

</section>