<?php

use classes\Config;
use classes\Core;
use classes\Routes ;

/**
 * @var $data
 */

//get number of page
$nPage = (int) (Routes::getParams()[1] ?? 1 ) ;

?>

<section id="home-slide">
    <div id="daily-movies-slider" class=" carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#daily-movies-slider" data-bs-slide-to="0" class="active"
                    aria-current="true"></button>
            <button type="button" data-bs-target="#daily-movies-slider" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#daily-movies-slider" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <?php foreach (array_slice($data->items["articles"], 0, 3) as $key => $article): ?>
                <div class="carousel-item <?= ($key === 0) ? 'active' : '' ?>">
                    <a href="/article/<?= $article->id . '/' . $article->slug ?>">
                        <img src="<?= Config::PATCH_IMG_FULL . $article->image ?>" class="d-block w-100" alt="...">
                    </a>
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="fw-bold " style="text-shadow: #000 0px 0 20px;"><?= $article->title ?></h1>
                        <p class="fw-bold fs-3"
                           style="text-shadow: #000 0px 0 20px;"><?= substr($article->content, 0, 255) ?>...</p>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#daily-movies-slider"
                data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#daily-movies-slider"
                data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <div class="  container-fluid mt-5">
        <div class="row gx-3">
            <?php foreach (array_slice($data->items["articles"], 3, 4) as $key => $article): ?>
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
            <?php if($nPage > 1):?>
                <li class="page-item"><a class="page-link" href="/home/<?= $nPage - 1 ?>">Précédent</a></li>
            <?php endif?>
            <?php foreach ($data->items["pages"] as $page): ?>

                <li class="page-item <?= ((int) $nPage === $page ) ? 'active' :'' ?> ">
                    <a class="page-link " href="/home/<?= $page ?>"><?= $page ?></a>
                </li>
            <?php endforeach; ?>
            <?php if(count($data->items["pages"]) !== $nPage ):?>
                <li class="page-item"><a class="page-link" href="/home/<?= $nPage + 1 ?>">Suivant</a></li>
            <?php endif?>
        </ul>
    </div>

</section>

