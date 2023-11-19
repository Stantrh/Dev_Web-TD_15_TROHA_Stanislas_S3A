<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;

class AddPlaylistAction extends Action
{

    public function execute(): string
    {
        $contenu_html = "";
        if($this->http_method === 'GET'){
            $contenu_html .= <<<END
               <form class="content"  method="post" action="?action=add-playlist">
               <label for="nom_playlist">Nom de la playlist : </label>
               <input type="text" id="nom_playlist" name="nom_playlist" required><br><br>
               <input type="submit" value="Envoyer">
               </form>
    END;
        }elseif($this->http_method === 'POST'){
            // Vérifier que la valeur entrée dans le formulaire est clean pour instancier la playlist
            $nom = filter_var($_POST['nom_playlist'], FILTER_SANITIZE_STRING);
            if($_POST['nom_playlist'] === $nom){
                // Instanciation d'une playlist avec le nom récupéré du formulaire
                $playlist = new Playlist($nom);

                $serialPlaylist = serialize($playlist);
                // enregistrement de la playlist dans la session PHP
                $_SESSION['playlist'] = $serialPlaylist;

                // Puis l'afficher avec un AudioListRenderer
                $renderer = new AudioListRenderer($playlist);
                $contenu_html .= $renderer->render();
                // Et ajout du lien pour ajouter une piste
                $contenu_html .= '<a href="?action=add-podcasttrack">Ajouter une piste</a>';
            }else{
                $contenu_html .= "le nom de playlist donné n'est pas sécurisé";
            }
        }
        return $contenu_html;
    }
}