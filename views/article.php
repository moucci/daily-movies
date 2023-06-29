<?php

use classes\Core ;
use classes\Config;
/**
 * @var $data
 */

?>


<section>
    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <img src="<?= Config::PATCH_IMG_FULL . $data->article->image ?>" class="float-start  xl-50 me-3 mb-3 rounded img-fluid"  alt="...">
                <h1 class="m-5 text-center"><?= $data->article->title ?></h1>
                <p><?= $data->article->content ?>
                    <?= $data->article->content ?><?= $data->article->content ?>
                </p>
            </div>
        </div>
    </div>


    <div class="container">

        <?php foreach ($data->article->cats as $value): ?>
            <a href="/categories/<?= $value?>" class="btn m-1 btn-dark">#<?= $value?></a>
        <?php endforeach;?>

    </div>

</section>
