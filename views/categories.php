<?php

use classes\Core;
use classes\Config;
use classes\Routes;

/**
 * @var $data
 */
//get number of page
$nPage = (int)(Routes::getParams()[2] ?? 1);

//get cate in url
$categorie = htmlspecialchars(Routes::getParams()[1]);

?>
<section id="categorie">

    <?php if (empty($data->items["articles"])): ?>
    <div class="container text-center  mt-4">
        <h1 class="fs-2">Nos catégories ressemblent à un désert sans articles,
            nos auteurs ont pris des vacances à durée indéterminée ! Woula on est désolé</h1>

        <div class="position-relative ">
            <img src="/public/assets/icons/big-icon-player.jpg" class=" pt-5 w-75 mt-5 opacity-25  d-block mx-auto" alt="">
            <h2 class="fs-6 fw-light position-absolute top-0 start-50 w-100 translate-middle text-center">
                On vous fait de la peine ? Vous voulez écrire sans être payé ? Vous êtes au bon endroit !
                Devenez auteur chez nous !
            </h2>
            <a  style="margin-top:65px"  class="position-absolute top-0 start-50 translate-middle   btn btn-outline-dark btn-lg" href="/inscription">Je veux bien </a>

        </div>
    </div>

    <?php endif; ?>

    <div class="  container-fluid mt-5">
        <div class="row gx-3">
            <?php foreach ($data->items["articles"] as $key => $article): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <article class="card mb-5  ">
                        <a href="/article/<?= $article->id . '/' . $article->slug ?>">
                            <img src="<?= Config::PATCH_IMG_FULL . $article->image ?>" class="d-block w-100" alt="...">
                        </a>
                        <div class="card-body">
                            <h4 class="card-title fst-italic   text-capitalize">
                                <a class="text-decoration-none"
                                   href="/article/<?= $article->id . '/' . $article->slug ?>">
                                    <?= $article->title ?>
                                </a>
                            </h4>

                            <p class="card-text text-wrap fw-bold ">
                                <?= substr($article->content, 0, 255) ?>...</p>
                        </div>
                        <div class="d-flex text-capitalize px-3  justify-content-between align-items-baseline">
                            <p class=""> <?= $article->nom ?> <?= $article->prenom ?></p>

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
                <li class="page-item"><a class="page-link" href="/categories/<?= $categorie ?>/<?= $nPage - 1 ?>">Précédent</a>
                </li>
            <?php endif ?>
            <?php foreach ($data->items["pages"] as $page): ?>

                <li class="page-item <?= ((int)$nPage === $page) ? 'active' : '' ?> ">
                    <a class="page-link " href="/categories/<?= $categorie ?>/<?= $page ?>"><?= $page ?></a>
                </li>
            <?php endforeach; ?>
            <?php if (count($data->items["pages"]) > $nPage && $nPage >= 1): ?>
                <li class="page-item"><a class="page-link"
                                         href="/categories/<?= $categorie ?>/<?= $nPage + 1 ?>">Suivant</a></li>
            <?php endif ?>
        </ul>
    </div>

</section>
