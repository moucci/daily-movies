<main>
    <form action="/inscription" method="POST" class="d-flex flex-column justify-content-evenly align-items-center">
        <span class="mt-5 mb-2 text-danger"><?= (isset($data->erreurs->nom)) ? $data->erreurs->nom :''?></span>
        <input type="text" value="<?= (isset($_POST["nom"]))? htmlspecialchars($_POST["nom"]) :''?>"
               name="nom" id="nom" placeholder="Nom"
               class=" p-2 border border-3 border-secondary-subtle rounded text-center">

        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->prenom)) ? $data->erreurs->prenom :''?></span>
        <input type="text" value="<?= (isset($_POST["prenom"]))? htmlspecialchars($_POST["prenom"]) :''?>"
               name="prenom" id="prenom" placeholder="Prénom"
               class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->email)) ? $data->erreurs->email :''?></span>
        <input type="email" value="<?= (isset($_POST["email"]))? htmlspecialchars($_POST["email"]) :''?>"
               name="email" id="email" placeholder="Email"
               class=" p-2 border border-3 border-secondary-subtle rounded text-center">


        <span class="mt-3 mb-2 text-danger "><?= (isset($data->erreurs->mdp)) ? $data->erreurs->mdp :''?></span>
        <input type="password" value="<?= (isset($_POST["mdp"]))? htmlspecialchars($_POST["mdp"]) :''?>"
               name="mdp" id="mdp" placeholder="Mot de passe"
               class=" p-2 border border-3 border-secondary-subtle rounded text-center">

        <div class="form-check form-switch m-2">
            <input class="form-check-input"
                    <?= (isset($_POST["rgpd"]) && $_POST["rgpd"] )? "checked" :""?>
                   type="checkbox"  name="rgpd" value="1" id="flexSwitchCheckDefault">
            <label class="form-check-label <?= (isset($data->erreurs->rgpd)) ? 'text-danger' :''?>" for="flexSwitchCheckDefault">
                J'accepte la collecte et le traitement de mes données
            </label>
        </div>

        <p class="mt-2 text-secondary">Le mot de passe doit contenir au moins : </p>
        <ul class="text-secondary">
            <li>Une majsucule</li>
            <li>Une minuscule</li>
            <li>Un chiffre</li>
            <li>Un caractère spécial parmis ( @?#"!+$*&_\-^% ) </li>
        </ul>

        <span class="mt-3 mb-2 text-danger"><?= (isset($data->erreurs->autre)) ? $data->erreurs->autre :''?></span>

        <button class="btn btn-secondary mt-3">S'inscrire</button>
    </form>
</main>
