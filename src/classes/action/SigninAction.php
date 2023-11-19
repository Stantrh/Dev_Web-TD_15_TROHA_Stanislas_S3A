<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\render\AudioListRenderer;

class SigninAction extends Action
{

    /**
     * @throws AuthException
     */
    public function execute(): string
    {
        $contenu_html = "";
        if($this->http_method === 'GET'){
            $contenu_html .= <<<FORM
<form class="content" action="?action=signin" method="post">
 <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="password">Mot de passe : </label>
            <input type="password" id="password" name="password" required><br><br>
    <input type="submit" name="submit" value="Se connecter">
</form>
FORM;
        }elseif($this->http_method === 'POST'){
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            try {
                Auth::authenticate($email, $_POST['password']);
            } catch (AuthException $e) {
                echo'error'.$e->getMessage();
            }
            // On affiche désormais les playlist de l'utilisateur
            $user = unserialize($_SESSION['user']);
            $tab = $user->getPlaylists();

            foreach($tab as $t){
                $renderer = new AudioListRenderer($t);
                $contenu_html .= $renderer->render();

            }
        }
        return $contenu_html;
    }
}