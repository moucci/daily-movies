<?php

use classes\Config;

if (isset($_POST['file'])):?>
    <div class="container  my-4">
        <img src="<?= Config::PATCH_IMG_FULL . htmlspecialchars($_POST['file']) ?>"
             class=" w-50  d-block m-auto  rounded " alt="...">
    </div>
<?php endif; ?>
<form class="container-fluid position-relative m-auto mt-3" style="max-width: 700px" method="POST"
      enctype="multipart/form-data">
    <div class="d-flex flex-column">
        <?php if (isset($_POST['file'])): ?>
            <div class="container ">
                <input type="hidden" name="file" value="<?= htmlspecialchars($_POST['file']) ?>">
            </div>
        <?php endif; ?>
        <div class="form-input">
            <?php if (isset($data->response["erreurs"]->title)) : ?>
                <span class=" my-3 d-block text-danger"><?= $data->response["erreurs"]->title ?></span>
            <?php endif; ?>
            <div class="input-group   mb-3">
                <label class="fw-bold input-group-text" id="form-title" for="title">Title Pour l'article</label>
                <input type="text" placeholder="exemple : Sortie de Spiderman 4 ( min: 50 characters )"
                       value="<?= (!empty($_POST['title']) ? htmlspecialchars($_POST['title']) : '') ?>"
                       name="title" id="title" class="form-control " style="max-width: 500px;">
            </div>
        </div>

        <div class="form-input">
            <?php if (isset($data->response["erreurs"]->slug)) : ?>
                <span class=" my-3 d-block  text-danger"><?= $data->response["erreurs"]->slug ?></span>
            <?php endif; ?>
            <div class="input-group  mb-3">
                <label class=" fw-bold input-group-text" id="form-title" for="title">Slug de l'article</label>
                <input type="text" placeholder="exemple : sortie-spiderman-4"
                       value="<?= (!empty($_POST['slug']) ? htmlspecialchars($_POST['slug']) : '') ?>"
                       name="slug" id="slug" class="form-control " style="max-width: 500px;">
            </div>

        </div>
        <div class="form-input-file">
            <?php if (isset($data->response["erreurs"]->file)) : ?>
                <span class=" my-3 d-block  text-danger"><?= $data->response["erreurs"]->file ?></span>
            <?php endif; ?>
            <div class=" mb-3">
                <label class="fw-bold my-1 ms-1" id="form-file" for="file">L'affiche de l'article</label>

                <!-- l'image de l'article -->
                <input type="file" class="form-control " name="image" id="image" class="mt-3">
            </div>

        </div>

        <div class="form-textarea ">
            <?php if (isset($data->response["erreurs"]->content)) : ?>
                <span class="  my-3 d-block text-danger"><?= $data->response["erreurs"]->content ?></span>
            <?php endif; ?>
            <label class="fw-bold my-1 ms-1" for="wyswig">Contenu de l'article</label>
            <textarea type="text" placeholder="Contenu de l'article , minimum de 300 characters" name="content"
                      id="wyswig"
                      class="mt-2 text-break overflow-visible rounded">
            <?= (!empty($_POST['content']) ? htmlspecialchars($_POST['content']) : '') ?>
        </textarea>
        </div>


        <!-- les catégories -->
        <div class="container-fluid my-4">
            <?php if (isset($data->response["erreurs"]->categories)) : ?>
                <span class="text-danger"><?= $data->response["erreurs"]->categories ?></span>
            <?php endif; ?>
            <fieldset class="d-block my-3">
                <label class="fw-bold fs-5" for="categories ">Ajouter une catégorie </label>
                <?php foreach ($data->response["categories"] as $categorie) : ?>
                    <div class="d-inline-block mb-2">
                        <input type="checkbox" name="categories[]"
                               class="btn-check"
                            <?= (isset($_POST["categories"]) && in_array($categorie->id, $_POST["categories"])) ? 'checked' : '' ?>
                               value="<?= $categorie->id ?>" id="check-<?= $categorie->id ?>" autocomplete="off">
                        <label class="btn btn-outline-dark"
                               for="check-<?= $categorie->id ?>"><?= $categorie->name ?></label>
                    </div>
                <?php endforeach; ?>
                <a class="btn btn-outline-dark fw-bold " href="/gestions/categories"><i
                            class="bi bi-patch-plus  me-2"></i>Ajouter une catégorie</a>
            </fieldset>


        </div>

        <div>

            <?php if (isset($erreurs['autre'])) : ?>
                <span class="text-danger"><?= $erreurs['autre'] ?></span>
            <?php endif; ?>

            <button aria-label="envoyer le formulaire" style="width: 200px"
                    class="my-2 m-auto d-block  btn btn-success text-center border border-secondary rounded-pill">
                Envoyer
            </button>
        </div>

    </div>
</form>
<style>
    .ck-powered-by {
        display: none !important;
    }
</style>
<script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>
<script type="text/javascript">
    ClassicEditor
        .create(document.querySelector('#wyswig'), {
            toolbar: {
                items: [
                    'undo',
                    'redo',
                    'heading',
                    '|',
                    'alignment',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    'blockQuote',
                ]
            },
            heading: {
                options: [
                    {model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph'},
                    {model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1'},
                    {model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2'}
                ]
            },
            entities: false,
            basicEntities: false,
            entities_greek: false,
            entities_latin: false,
        })
        .catch(error => {
            console.error(error);
        });

    console.log(ClassicEditor)
</script>
