<?php

use classes\Config;

//\classes\Core::var_dump_pre($data);

//die ;
?>
<div class="container  my-4">
     <h1 class="text-center">Ajouter ou supprimer une catégorie </h1>
</div>
<form class="container-fluid position-relative m-auto mt-3" style="max-width: 700px" method="POST"
      enctype="multipart/form-data">
    <div class="d-flex flex-column">

        <div class="form-input">
            <?php if (isset($data->response["erreurs"]->name)) : ?>
                <span class=" my-3 d-block text-danger"><?= $data->response["erreurs"]->name ?></span>
            <?php endif; ?>
            <div class="input-group   mb-3">
                <label class="fw-bold input-group-text" id="form-cat" for="name">Nom de catégorie</label>
                <input type="text" placeholder="exemple : action-aventure"
                       value="<?= (!empty($_POST['name']) ? htmlspecialchars($_POST['name']) : '') ?>"
                       name="name" id="name" class="form-control " style="max-width: 500px;">
            </div>
        </div>
        <div class="form-input">
            <?php if (isset($data->response["erreurs"]->slug)) : ?>
                <span class=" my-3 d-block text-danger"><?= $data->response["erreurs"]->slug ?></span>
            <?php endif; ?>
            <div class="input-group  mb-3">
                <label class="fw-bold input-group-text" id="form-cat" for="sloug">Slug pour la catégorie</label>
                <input type="text" placeholder="exemple : action-aventure"
                       value="<?= (!empty($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '') ?>"
                       name="slug" id="slug" class="form-control " style="max-width: 500px;">
            </div>
        </div>




        <!-- les catégories -->
        <div class="container-fluid my-4">
            <?php if (isset($data->response["erreurs"]->categories)) : ?>
                <span class="text-danger"><?= $data->response["erreurs"]->categories ?></span>
            <?php endif; ?>
            <fieldset>
                <label class="fw-bold fs-5" for="categories ">Supprimer une catégorie :</label>
                <?php foreach ($data->categories as $categorie) : ?>
                    <div class="d-inline-block mb-2">
                        <input type="checkbox" name="categories[]"
                               class="btn-check"
                            <?= (isset($_POST["categories"]) && in_array($categorie->id, $_POST["categories"])) ? 'checked' : '' ?>
                               value="<?= $categorie->id ?>" id="check-<?= $categorie->id ?>" autocomplete="off">
                        <label class="btn btn-outline-dark" onclick="return confirm('Êtes-vous sûr de vouloir supprimer la catégorie <?= $categorie->name ?>')"
                               for="check-<?= $categorie->id ?>"><i class="bi bi-x  me-2"></i><?= $categorie->name ?></label>


                    </div>
                <?php endforeach; ?>

            </fieldset>
        </div>


        <div>
            <?php if (isset($reponseimage['message_erreur'])) : ?>
                <span class="text-danger"><?= $reponseimage['message_erreur'] ?></span>
            <?php endif; ?>

            <?php if (isset($erreurs['autre'])) : ?>
                <span class="text-danger"><?= $erreurs['autre'] ?></span>
            <?php endif; ?>

            <button   aria-label="envoyer le formulaire" style="width: 200px"
                    class="my-2 m-auto d-block  btn btn-success text-center border border-secondary rounded-pill">
                Envoyer
            </button>
        </div>

    </div>
</form>