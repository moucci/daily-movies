<?php

use classes\Config;

/**
 * @var $data
 */

//\classes\Core::var_dump_pre($data->response);
//die ;
?>
<div class="container  my-4">
    <h1 class="text-center">Ajouter ou supprimer une catégorie </h1>

    <?php if (isset($data->response['process']) && $data->response['process']->add === true): ?>
        <p style="max-width: 400px" class="my-3 mx-auto alert alert-success text-center" role="alert">
            La Catégorie à bien était ajoutée.
        </p>
    <?php endif; ?>
    <?php if (isset($data->response['process']) && $data->response['process']->remove === true): ?>
        <p style="max-width: 400px" class="my-3 mx-auto alert alert-success text-center" role="alert">
            Les catégories ont bien été supprimées.
        </p>
    <?php endif; ?>

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
                <span class="text-danger d-block  mb-5"><?= $data->response["erreurs"]->categories ?></span>
            <?php endif; ?>
            <fieldset>
                <label class="fw-bold fs-5" for="categories ">Supprimer une catégorie :</label>
                <?php foreach ($data->categories as $categorie) : ?>
                    <div class="d-inline-block mb-2">
                        <input type="checkbox" name="categories[]"
                               class="btn-check"
                            <?= (isset($_POST["categories"]) && in_array($categorie->id, $_POST["categories"])) ? 'checked' : '' ?>
                               value="<?= $categorie->id ?>" id="check-<?= $categorie->id ?>" autocomplete="off">
                        <label class=" btn-delete btn btn-outline-dark"

                               for="check-<?= $categorie->id ?>">
                            <i class="bi bi-x me-2"></i><?= $categorie->name ?>
                        </label>


                    </div>
                <?php endforeach; ?>

            </fieldset>
        </div>


        <div>
            <?php if (isset($data->response["erreurs"]->autre)) : ?>
                <span class="text-danger"><?= $data->response["erreurs"]->autre ?></span>
            <?php endif; ?>

            <button aria-label="envoyer le formulaire" style="width: 200px"
                    class="my-2 m-auto d-block  btn btn-success text-center border border-secondary rounded-pill">
                Envoyer
            </button>
        </div>

    </div>
</form>
<script>

    let $btnDelete = document.querySelectorAll('.btn-delete');
    $btnDelete.forEach(($e) => {
        $e.addEventListener('click', function (event) {
            event.preventDefault();
            let attr = this.getAttribute('for');
            let checkbox = document.querySelector('#' + attr);
            let value = checkbox.value;
            if (!checkbox.checked) {
                let confirmation = confirm(`Êtes-vous sûr de vouloir supprimer la catégorie "${value}" ?`);
                if (confirmation) {
                    checkbox.checked = true;
                }
            } else {
                checkbox.checked = false;
            }

        });
    });

</script>