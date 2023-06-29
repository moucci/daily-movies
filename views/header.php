<?php
/**
 * Value returned by  catégorieController
 * @var TYPE_NAME $listCats
 */
//echo "<pre>" ;
//var_dump($_SESSION);
//echo "</pre>" ;



?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/home">
                <img class="d-inline-block align-text-top" width="50" height="50" src="/public/assets/icons/logo.png" alt="">
            </a>

            <h1 class=" navbar-brand fs-1 text-light ">Daily Movies</h1>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="btn btn-dark text-capitalize font-weight-bold" href="/home">Accueil</a>
                    </li>
                    <?php $count = min(count($listCats), 6);
                    for ($i = 0; $i < $count; $i++): ?>
                        <li class="nav-item">
                            <a class="btn btn-dark text-capitalize font-weight-bold" href="<?= $listCats[$i]->slug ?>"><?= $listCats[$i]->name ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if (count($listCats) > 6): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-capitalize font-weight-bold dropdown-toggle dropdown-toggle-split" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Plus...
                            </a>
                            <div class="dropdown-menu dropdown-menu-end  border-0 dropdown-menu-dark bg-dark" aria-labelledby="navbarDropdown">
                                <?php for ($i = 6; $i < count($listCats); $i++): ?>
                                    <a class="dropdown-item text-capitalize font-weight-bold" href="<?= $listCats[$i]->slug ?>"><?= $listCats[$i]->name ?></a>
                                <?php endfor; ?>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['is_connected'])): ?>
                        <li class="nav-item">
                            <a class="btn btn-dark" title="tableau de bord" href="/gestions">Dashboard</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>