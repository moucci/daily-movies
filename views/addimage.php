<?php

/**
 * $image = $_FILES['image']
 */
function addimage($image)
{

    // on vérifie l'extension et le type Mime
    $allowed = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
    ];

    $filename = $image['name'];
    $filetype = $image['type'];
    $filesize = $image['size'];

    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    if (!array_key_exists($extension, $allowed) || !in_array($filetype, $allowed)) {
        $erreurs['image'] = 'Format de fichier incorrect.';
    }

    //on limite à 1 Mio
    if ($filesize > 1024 * 1024) {
    }

    //on génère un nom unique
    $newname = md5(uniqid());

    //on génère le chemin

    $newfilename = __DIR__ . "../../public/assets/images/square/$newname.$extension";


    $_SESSION['nomimage'] = "$newname.$extension";

    if (!move_uploaded_file($image['tmp_name'], $newfilename)) {
        $erreurs['upload'] = 'l\'upload à échouer';
    }

    chmod($newfilename, 0644);

    $dimension = getimagesize($newfilename);

    $largeur = $dimension[0];
    $hauteur = $dimension[1];


    switch ($dimension['mime']) {
        case 'image/png':
            $imagesource = imagecreatefrompng($newfilename);
            break;

        case 'image/jpeg':
            $imagesource = imagecreatefromjpeg($newfilename);
            break;
    }

    if ($largeur <= $hauteur) {
        $nouvelleimage = imagecreatetruecolor($largeur, $largeur);
        $taille = intval(((100 - (($largeur * 100) / $hauteur)) / 2) * $hauteur / 100);

        imagecopyresampled(
            $nouvelleimage,
            $imagesource,
            0,
            0,
            0,
            $taille,
            $largeur,
            $hauteur,
            $largeur,
            $hauteur,
        );
    }

    if ($largeur > $hauteur) {
        $nouvelleimage = imagecreatetruecolor($hauteur, $hauteur);
        $taille = intval(((100 - (($hauteur * 100) / $largeur)) / 2) * $largeur / 100);

        imagecopyresampled(
            $nouvelleimage,
            $imagesource,
            0,
            0,
            $taille,
            0,
            $largeur,
            $hauteur,
            $largeur,
            $hauteur,
        );
    }

    $fullimage = imagecreatetruecolor($largeur, intval(9 * 100 / 16 * $largeur / 100));
    $taille = intval(((100 - (($hauteur * 100) / $largeur)) / 2) * $largeur / 100);

    imagecopyresampled(
        $fullimage,
        $imagesource,
        0,
        0,
        0,
        $taille,
        $largeur,
        $hauteur,
        $largeur,
        $hauteur,
    );
    

    switch ($dimension['mime']) {
        case 'image/png':
            imagepng($nouvelleimage, $newfilename);
            imagepng($fullimage, __DIR__ . "../../public/assets/images/full/$newname.$extension");
            break;

        case 'image/jpeg':
            imagejpeg($nouvelleimage, $newfilename);
            imagejpeg($fullimage, __DIR__ . "../../public/assets/images/full/$newname.$extension");

            break;
    }

    imagedestroy($imagesource);
    imagedestroy($nouvelleimage);

}