<?php

use classes\Core;
use classes\Config;
use classes\Routes;

/**
 * @var $data
 */
//get number of page
$nPage = (int)(Routes::getParams()[1] ?? 1);
?>
<section id="articles">

    <?php if (empty($data->items["articles"])): ?>
        <div class="container text-center  mt-4">
            cette article n'existe pas
        </div>
    <?php endif; ?>

    <div class="container-fluid bg-dark">
        <div class="container  text-md-start ">
            <a href="/gestions/new" class="btn h6 m-3 fw-bold btn-light">Ajouter un article</a>
            <a href="/gestions/categorie" class="btn h6 m-3 fw-bold btn-light">Ajouter une Catégorie</a>
        </div>
    </div>
    <h2 class="h2 m-5 text-center text-uppercase fw-bold">Listes des articles </h2>

    <div class="  container-fluid mt-5">
        <div class="row gx-3">
            <?php foreach ($data->items["articles"] as $key => $article): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <article class="card mb-5  ">
                        <a href="/article/<?= $article->id . '/' . $article->slug ?>">
                            <img src="<?= Config::PATCH_IMG_SQUARE . $article->image ?>" class="d-block w-100" alt="...">
                        </a>
                        <div class="card-body">
                            <h4 class="card-title fst-italic d-flex justify-content-between   text-capitalize">
                                <a class="text-decoration-none"
                                   href="/article/<?= $article->id . '/' . $article->slug ?>">
                                    <?= $article->title ?>
                                </a>
                                <a href="gestions/edit/<?= $article->id ?>" class="btn fw-bold btn-primary">Editer</a>

                            </h4>

<!--                            <p class="card-text text-wrap fw-bold ">-->
<!--                                --><?php //= substr($article->content, 0, 255) ?><!--...</p>-->
                        </div>
                        <div class="d-flex text-capitalize px-3  justify-content-between align-items-baseline">
                            <p class="">Par :  <?= $article->nom ?> <?= $article->prenom ?></p>

                            <p class=" fs-7 fw-light"> Créé le : <?= $article->date_creation ?></p>

                        </div>
                    </article>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

    <div class="mt-auto">
        <ul class="pagination pagination-lg justify-content-center">
            <!--            <li class="page-item"><a class="page-link" href="#">Précédent</a></li>-->
            <?php if ($nPage > 1): ?>
                <li class="page-item"><a class="page-link" href="/gestions/<?= $nPage - 1 ?>">Précédent</a>
                </li>
            <?php endif ?>
            <?php foreach ($data->items["pages"] as $page): ?>

                <li class="page-item <?= ((int)$nPage === $page) ? 'active' : '' ?> ">
                    <a class="page-link " href="/gestions/<?= $page ?>"><?= $page ?></a>
                </li>
            <?php endforeach; ?>
            <?php if (count($data->items["pages"]) > $nPage && $nPage >= 1): ?>
                <li class="page-item"><a class="page-link"
                                         href="/gestions/<?= $nPage + 1 ?>">Suivant</a></li>
            <?php endif ?>
        </ul>
    </div>

</section>


