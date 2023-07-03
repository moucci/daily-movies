<?php
/**
 * Value returned by  catégorieController
 * @var TYPE_NAME $listCats
 */
?>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" aria-label="Revenir à la page d'acceuil"  href="/home">
                <img class="d-inline-block align-text-top" width="50" height="50" src="/public/assets/icons/logo.png"
                     alt="">
            </a>

            <h1 class=" navbar-brand fs-1 text-light ">Daily Movies</h1>
            <button aria-label="menu" class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse align-items-center justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item d-flex align-items-center">
                        <a class="btn btn-dark text-capitalize font-weight-bold" href="/home">Accueil</a>
                    </li>

                    <?php $count = min(count($listCats), 6);
                    for ($i = 0; $i < $count; $i++): ?>
                        <li class="nav-item d-flex align-items-center">
                            <a class="btn btn-dark text-capitalize font-weight-bold"
                               href="<?= $listCats[$i]->slug ?>"><?= $listCats[$i]->name ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if (count($listCats) > 6): ?>
                        <li class="nav-item d-flex align-items-center dropdown">
                            <a class="nav-link text-capitalize font-weight-bold dropdown-toggle dropdown-toggle-split"
                               href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                Plus...
                            </a>
                            <div class="dropdown-menu dropdown-menu-center  border-0 dropdown-menu-dark bg-dark"
                                 aria-labelledby="navbarDropdown">
                                <?php for ($i = 6; $i < count($listCats); $i++): ?>
                                    <a class="dropdown-item text-capitalize font-weight-bold"
                                       href="/categories/<?= $listCats[$i]->slug ?>"><?= $listCats[$i]->name ?></a>
                                <?php endfor; ?>
                            </div>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['is_connected'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-capitalize font-weight-bold dropdown-toggle dropdown-toggle-split"
                               href="#" id="navbarUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <?= strtolower(substr($_SESSION['name'] , 0 , 1)) .'.' .ucfirst($_SESSION['prenom']) ?>
                                <i class="bi fs- ms-2 bi-person-circle"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded border-0 dropdown-menu-dark bg-dark"
                                 aria-labelledby="navbarUser">
                                <a  class="dropdown-item text-capitalize font-weight-bold" title="tableau de bord" href="/gestions">
                                    <i class="bi bi-clipboard me-2 mt-2"></i>
                                    Dashboard
                                </a>
                                <a  class="dropdown-item text-capitalize font-weight-bold" href="/logout">
                                    <i class="bi bi-box-arrow-left me-2 mt-2 "></i>
                                    Déconnexion
                                </a>
                            </div>
                        </li>
                    <?php else:?>
                        <li class="nav-item dropdown">
                            <a class="nav-link text-capitalize font-weight-bold dropdown-toggle dropdown-toggle-split"
                               href="#" id="navbarUser" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <i class="bi fs-4 bi-person-lines-fill"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end rounded border-0 dropdown-menu-dark bg-dark"
                                 aria-labelledby="navbarUser">
                                <a  class="dropdown-item text-capitalize font-weight-bold" title="tableau de bord" href="/connexion">
                                    <i class="bi bi-box-arrow-right me-3 mt-2"></i>
                                    Connexion
                                </a>
                                <a  class="dropdown-item text-capitalize font-weight-bold" href="/inscription">
                                    <i class="bi  bi-box-arrow-left me-3 mt-2 "></i>
                                    Inscription
                                </a>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>