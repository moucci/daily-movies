<?php

namespace classes\controllers;
use classes\Core;
use classes\Db;
use PDO;
use stdClass;

class Signin extends MainController
{
    protected string $email;

    protected string $mdp;

    public function __construct()
    {

        //if no data , return  empty form
        if (empty($_POST)) {
            MainController::render("connexion", [
                "title" => "page de connexion"
            ]);
            die();
        }
        //try to bind data
        $this->email = $_POST['email'] ?? '';
        $this->mdp = $_POST['mdp'] ?? '';

        //else check data
        $this->checkData();
    }

    /**
     * methode to check $email && $mdp
     * @return void
     */
    private function checkData()
    {
        $erreurs = new stdClass();

        $validationMail = Core::checkEmail($this->email);
        if ($validationMail !== true) {
            $erreurs->email = $validationMail;
        }

        // Vérifier la variable vide
        if (empty($this->mdp)) {
            $erreurs->mdp = "Le champ mot de passe est requis.";
        }

        // Vérifier s'il y a des erreurs
        if (!empty(get_object_vars($erreurs))) {
            MainController::render("connexion", [
                "title" => "page de connexion",
                "erreurs" => $erreurs
            ]);
            die();
        }


        //check to connect Authors
        if (!$this->loginAuthor()) {
            $erreurs->autre = "Email ou Mot de passe incorrect.";
            MainController::render("connexion", [
                "title" => "page de connexion",
                "erreurs" => $erreurs
            ]);
            die();
        } else {
            Header('Location: /gestions');
        }
    }

    /**
     * Methode to login Author
     * set session if process success
     * @return string|true error message | bool
     */
    private function loginAuthor(): bool|string
    {
        //GET DB CONNEXION
        $db = Db::getDb();

        $req = $db->prepare('SELECT id ,  nom as name , email , mdp as mdp_hashed FROM users  where email = :email');
        $req->bindParam(':email', $this->email, PDO::PARAM_STR);

        ////try to get users by may
        if (!$req->execute()) return 'failed';

        //if user not existe
        if ($req->rowCount() === 0) return 'error';
        $data = $req->fetch(PDO::FETCH_OBJ);
        $req->closeCursor();
        //check pass word hash
        if (!password_verify($this->mdp, $data->mdp_hashed)) {
            return 'error';
        }

        //set session
        $_SESSION['is_connected'] = true;
        $_SESSION['name'] = $data->name;
        $_SESSION['id'] = $data->id;

        return true;
    }

}