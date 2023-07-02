<?php

namespace classes;

use classes\controllers\Articles;
use PDO;

class Core
{

    /**
     * convertit string with "-" to CamelCase
     * @param string $string
     * @param bool $capitalizeFirstCharacter
     * @return array|string
     */
    public static function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false): array|string
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        if (!empty($str) && !$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

    /**
     * methode pour le débugage , en envirenement local seulement
     * @param mixed $value
     * @return void
     */
    public static function var_dump_pre(mixed $value)
    {

        echo '<pre>';

        var_dump($value);

        echo "</echo>";

    }


    /**
     * Valider le nom ou le prénom
     * @param string $name Le nom du champ
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public static function checkName(string $name, string $value): bool|string
    {
        $value = trim($value);

        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ $name est requis.";
        }
        // Vérifier la longueur
        if (strlen($value) < 1 || strlen($value) > 100) {
            return "La longueur du $name est incorrecte. Le $name doit comporter entre 2 et 50 caractères.";
        }
        // Vérifier les caractères spéciaux
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s\'\-]+$/', $value)) {
            return "Le $name contient des caractères spéciaux non autorisés.";
        }

        return true;
    }

    /**
     * Valider l'adresse email
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public static function checkEmail(string $value): bool|string
    {
        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ adresse email est requis.";
        }
        // Vérifier la validité de l'adresse email
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "L'adresse email est invalide.";
        }
        return true;
    }

    /**
     * Valider le mot de passe
     * @param string $value La valeur à valider
     * @return bool|string True si la valeur est valide, sinon le message d'erreur
     */
    public static function checkPass(string $value): bool|string
    {
        // Vérifier la variable vide
        if (empty($value)) {
            return "Le champ mot de passe est requis.";
        }
        // Vérifier la longueur
        if (strlen($value) < 16) {
            return "La longueur du mot de passe est incorrecte. Le mot de passe doit comporter au moins 16 caractères.";
        }
        // Vérifier les caractères requis
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@?!"+$*#&_\-^%])[A-Za-z\d@?#"!+$*&_\-^%]{16,}$/', $value)) {
            return "Le mot de passe est invalide. Il doit contenir au moins 16 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
        }
        return true;
    }


    /**
     * Methode to check  file image info
     * @param string $nameVarFiles key for file in $_FILES
     * @param string $typeSize check for SQUARE OR FULL IMAGE
     * @return bool|string
     */
    public static function checkImage(string $nameVarFiles, string $typeSize = 'SQUARE' | 'FULL'): bool|string
    {

        //get img
        $image = $_FILES[$nameVarFiles] ?? null;
        if (!$image['size']) return "Une image est obligatoire pour votre article de résolution min de 1200px de largeur et de 600px de hauteur";

        // Bind variables
        $imageType = $image['type'];
        $imageSize = $image['size'];
        $imageName = $image['name'];

        // Allowed extensions and MIME types
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $allowedMimeTypes = ['image/jpeg', 'image/png'];

        // Check the extension
        $extension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            return "Format de l'image non autorisé (jpg, jpeg, png).";
        }

        // Check the MIME type
        if (!in_array($imageType, $allowedMimeTypes)) {
            return "Type MIME de l'image non autorisé.";
        }
        //max sise allowed 9mo
        $maxSize = 9 * 1024 * 1024;
        if ($imageSize > $maxSize)
            return "Le poids de l'image dépasse les 9Mo autorisé";


        //get props image
        $fileNameTemp = $_FILES[$nameVarFiles]['tmp_name'];
        $widthMin = 1200;
        $heightMin = 600;
        list($width, $height) = getimagesize($fileNameTemp);
        //check width and height
        if ($width < $widthMin || $height < $heightMin)
            return "L'image doit avoir une largeur d'au moins 1200 pixels et une hauteur d'au moins 600 pixels.";

        return true;
    }

    /**
     * Methode to save image 16:9
     * @param string $nameVarFiles key index on $_FILES
     * @param string $pathFolderDest path for destination folder
     * @param string $typeImg FULL : 16:9 OR SQUARE 1:1
     * @param string | null $nameImage null : generate new nameImage
     * @return array [ process : bool , error : string , fileName : string  ]
     */
    public static function saveImage(string      $nameVarFiles,
                                     string      $pathFolderDest,
                                     string      $typeImg = 'FULL' | 'SQUARE',
                                     string|null $nameImage = null): array
    {
        //basic check file
        $image = $_FILES[$nameVarFiles] ?? null;
        if (!$image['size']) return [
            "process" => false,
            "error" => "Aucune image à sauvegarder"
        ];

        $tmpName = $image['tmp_name'];
        //get infos
        $infos = getimagesize($tmpName);

        // create basic img
        switch ($infos['mime']) {
            case 'image/png':
                $imgSource = imagecreatefrompng($tmpName);
                break;
            case 'image/jpeg':
                $imgSource = imagecreatefromjpeg($tmpName);
                break;
            default:
                return [
                    "process" => false,
                    "error" => "File mime n'est pas autorisé.",
                ];
                break;
        }

        // Get width and height of the source image
        $largeur = $infos[0];
        $hauteur = $infos[1];

        // Calculate the size of the square
        $newWidth = ($typeImg === 'SQUARE') ? min($largeur, $hauteur) : $largeur;
        $newHeight = ($typeImg === 'SQUARE') ? $newWidth : intval(9 * $largeur / 16);
        $emptyImage = imagecreatetruecolor($newWidth, $newHeight);
        // calculate position
        $offsetX = ($typeImg === 'SQUARE') ? intval(($largeur - $hauteur) / 2) : 0;
        $offsetY = ($typeImg === 'SQUARE') ? 0 : intval(($hauteur - $newHeight) / 2);;

        // Crop and copy the image to the square image
        imagecopy(
            $emptyImage,
            $imgSource,
            0,
            0,
            $offsetX,
            $offsetY,
            $newWidth,
            $newHeight,
        );


        //save file
        $newNameFile = (is_null($nameImage)) ? md5(uniqid()) . ".jpg" : $nameImage;
        imagejpeg($emptyImage, "." . $pathFolderDest . $newNameFile);

        //update chmod
        chmod("." . $pathFolderDest . $newNameFile, 0644);
        //destroy image
        imagedestroy($imgSource);
        imagedestroy($emptyImage);

        //return process
        return [
            "process" => true,
            "fileName" => $newNameFile
        ];

    }


}