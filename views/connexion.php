<main>
    <form action="/connexion" method="POST" class="d-flex flex-column justify-content-evenly align-items-center">
        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->email)) ? $data->erreurs->email :''?></span>
        <input type="text"  value="<?= (isset($_POST["email"]))? htmlspecialchars($_POST["email"]) :''?>" name="email" id="email"
               placeholder="Email" class=" p-2 border border-3 border-secondary-subtle rounded text-center">
        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->mdp)) ?  $data->erreurs->mdp:'' ?></span>
        <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" class=" p-2 border border-3 border-secondary-subtle rounded text-center">
        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->autre)) ? $data->erreurs->autre : ''?></span>
        <button aria-label="envoyer le formulaire" class="btn btn-secondary mt-3">Se connecter</button>
    </form>
</main>
