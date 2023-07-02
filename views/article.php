<?php

use classes\Core ;
use classes\Config;
/**
 * @var $data
 */

?>
<section class="mb-5">
    <div class="container ">
        <div class="row">
            <div class="col">
                <h1 class="m-5 text-center"><?= $data->article->title ?></h1>
                <img src="<?= Config::PATCH_IMG_FULL . $data->article->image ?>" class=" xl-50 me-3 mb-3 rounded img-fluid"  alt="...">
                <p><?= $data->article->content ?></p>
            </div>
        </div>
    </div>
    <div class="container">
        <?php foreach ($data->article->cats as $value): ?>
            <a href="/categories/<?= $value?>" class="btn m-1 btn-dark">#<?= $value?></a>
        <?php endforeach;?>
    </div>

</section>
