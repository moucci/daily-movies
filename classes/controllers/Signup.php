<?php

namespace classes\controllers;

use classes\Core;
use classes\Db;
use PDO;
use PDOException;
use stdClass;

class Signup extends MainController
{

    public function __construct()
    {
        //if no data , return  empty form
        if (empty($_POST)) {
            MainController::render("inscription", [
                "title" => "inscription"
            ]);
            die();
        }


        //else check data
        $this->checkData();
    }

    /**
     * methode to check $email && $mdp
     * @return void
     */
    private function checkData()
    {
        // Récupérer les données du formulaire
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['email'] ?? '';
        $mdp = $_POST['mdp'] ?? '';
        $rgpd = $_POST['rgpd'] ?? 0;

        $erreurs = new stdClass();

        //check mail
        $validationMail = Core::checkEmail($email);
        if ($validationMail !== true) {
            $erreurs->email = $validationMail;
        }

        // Vérification du champ nom
        $validationNom = Core::checkName('nom', $nom);
        if ($validationNom !== true) {
            $erreurs->nom = $validationNom;
        }

        // Vérification du champ prénom
        $validationPrenom = Core::checkName('prénom', $prenom);
        if ($validationPrenom !== true) {
            $erreurs->prenom = $validationPrenom;
        }

        // Vérification du champ mot de passe
        $validationMotDePasse = Core::checkPass($mdp);
        if ($validationMotDePasse !== true) {
            $erreurs->mdp = $validationMotDePasse;
        }

        //check rgpd
        if ($rgpd != 1) {
            $erreurs->rgpd = 'Veuillez accepter la collecte des vos informations';
        }

        // Vérifier s'il y a des erreurs
        if (!empty(get_object_vars($erreurs))) {
            MainController::render("inscription", [
                "title" => "page d'inscription",
                "erreurs" => $erreurs
            ]);
            die();
        }

        //try register author
        $process = $this->registerAuthor($email, $mdp, $nom, $prenom, $rgpd);
        if ($process !== true) {
            $erreurs->autre = $process;
            MainController::render("inscription", [
                "title" => "page de inscription",
                "erreurs" => $erreurs
            ]);
        } else {
            Header('Location: /gestions');
        }
    }

    /**
     * methode to register Author
     * @param string $email
     * @param string $mdp
     * @param string $nom
     * @param string $prenom
     * @param int $rgpd
     * @return string|void
     */
    private function registerAuthor(string $email, string $mdp, string $nom, string $prenom, int $rgpd)
    {
        //GET DB CONNEXION
        $db = Db::getDb();

        $query = "INSERT INTO users   (nom , prenom ,email , mdp , rgpd  )
                                    VALUES (:nom , :prenom , :email , :mdp , :rgpd)";

        //prepare query
        $req = $db->prepare($query);

        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $req->bindParam(':email', $email, PDO::PARAM_STR);
        //hash mdp
        $mdp = password_hash($mdp, PASSWORD_ARGON2ID, ['const' => 10]);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $req->bindParam(':rgpd', $rgpd, PDO::PARAM_INT);

        //try ton insert new conseiller
        try {
            $req->execute();
            //set session
            $_SESSION['is_connected'] = true;
            $_SESSION['name'] = $nom;
            $_SESSION['id'] = $db->lastInsertId();
            return true;
        } catch (PDOException $error) {
            if ($error->getCode() == '23000') {
                return 'Auteur deja inscrit';
            } else return 'Une erreur technique est survenue. Veuillez réessayer ultérieurement. Merci de votre compréhension.';
        }
    }

}