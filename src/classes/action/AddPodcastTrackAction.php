<?php

namespace iutnc\deefy\action;

use Exception;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;

class AddPodcastTrackAction extends Action
{

    public function execute(): string
    {
        $contenu_html = "";
        if($this->http_method === 'GET'){
            $contenu_html .= <<<END
    <form class="content" action="?action=add-podcasttrack" method="post" enctype="multipart/form-data">
            <label for="file">Sélectionnez un fichier (.mp3) :</label>
            <input type="file" id="fichier" name="fichier" required><br><br>
            
            <label for="text">Titre de votre son : </label>
            <input type="text" id="titre" name="titre" required><br><br>
            
            <label for="text">Nom de l'artiste : </label>
            <input type="text" id="nom_artiste" name="nom_artiste" required><br><br>
    
            <label for="number">Année de sortie : </label>
            <input type="number" id="annee" name="annee" required><br><br>
            
            <label for="text">Genre :</label>
            <input type="text" id="genre" name="genre" required><br><br>
    
            <label for="number">Duree (en s):</label>
            <input type="number" id="duree" name="duree"><br><br>
            
            <input type="submit" name="submit" value="Envoyer">
        </form>
    END;
        }elseif($this->http_method === 'POST'){
            try{
                // Vérifier si le fichier a bien été uploadé et qu'il est valide
                if(isset($_FILES['fichier']) && substr($_FILES['fichier']['name'],-4) === '.mp3' && $_FILES['fichier']['type'] === 'audio/mpeg') {

                    // ici on s'occupe de stocker le fichier
                    require __DIR__ .'/../../traitement_audio.php';

                    // On crée notre objet PodcastTrack
                    $podcast = new PodcastTrack($_POST['titre'], "../audio/" . $_FILES['fichier']['name']);
                    if (isset($_POST['nom_artiste'])) {
                        $podcast->__set('artiste', $_POST['nom_artiste']);
                    }
                    if (isset($_POST['genre'])) {
                        $podcast->__set('genre', $_POST['genre']);
                    }

                    if (isset($_POST['annee'])) {
                        $podcast->__set('annee', $_POST['annee']);
                    }

                    if (isset($_POST['duree'])) {
                        $podcast->__set('duree', $_POST['duree']);
                    }

                    // Récupérer la playlist depuis la session
                    $playlist = unserialize($_SESSION['playlist']);
                    $playlist->addTrack($podcast);
                    $_SESSION['playlist'] = serialize($playlist);

                    // enfin afficher la playlist avec AudioListRenderer
                    $renderer = new AudioListRenderer($playlist);

                    $contenu_html .= $renderer->render();

                    $contenu_html .= '<a href="?action=add-podcasttrack">Ajouter une nouvelle piste</a>';
                }
            }catch(Exception $e){
                $contenu_html .= $e->getMessage();

            }
        }
        return $contenu_html;
    }
}