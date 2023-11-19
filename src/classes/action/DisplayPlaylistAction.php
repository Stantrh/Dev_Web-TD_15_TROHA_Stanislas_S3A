<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action
{

    public function execute(): string
    {
        $contenu_html = "";
        if($this->http_method === 'GET'){
            try{
                if(isset($_SESSION['user'])){
                    $id = $_GET['id'];
                    Auth::checkPlaylistOwner($id);
                    $playlist = Playlist::find($id);
                    $renderer = new AudioListRenderer($playlist);
                    $contenu_html .= $renderer->render();
                }else{
                    $contenu_html .= "<h4>Vous devez être connecté pour pouvoir accéder à cette fonctionnalité</h4>";
                }

            }catch(AuthException $e){
                $contenu_html .= $e->getMessage();
            }
        }
        return $contenu_html;
    }
}