<?php
require '../vendor/autoload.php'; // Inclure l'autoloader de la bibliothèque

use Upload\Storage\FileSystem;
use Upload\File;
use Upload\Validation\Mimetype;
use Upload\Validation\Size;

// On configure l'endroit où on stocke les pistes des utilisateurs
$storage = new FileSystem('../audio');

$file = new File('fichier', $storage);

// On ajoute les types de fichiers audio acceptés
$file->addValidations([new Mimetype(['audio/mpeg', 'audio/mp3']), new Size('10M')]);

try {
    // Ici on le télécharge et upload va aussi faire son travail pour vérifier si le fichier est correct
    $file->upload();

//    echo 'Fichier MP3 uploadé avec succès.';
} catch (Exception $e) {
    echo 'Erreur lors de l\'upload du fichier : ' . $e->getMessage(); // A laisser ou pas je ne sais pas
}
