<?php

namespace iutnc\deefy\action;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\exception\AuthException;

/**
 * Classe qui permet l'ajout d'un utilisateur à la base de données
 */
class AddUserAction extends Action
{

    public function execute(): string // équivalent à retourner le contenu html
    {
        $contenu_html = "";
        // Vérifie si c'est un GET, si oui, affiche le formulaire
        // Mettre un script js pour que les deux mots de passe soient les mêmes (pour que l'utilisateur le voit)
        if ($this->http_method === 'GET') {
            $contenu_html .= <<<FORMULAIRE
<form id="registrationForm" class="content" action="?action=signup" method="post">
    <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmPassword">Confirmez le mot de passe :</label>
        <input type="password" id="confirmPassword" name="mdpConfirm" required>
        <p id="passwordMatchMessage"></p>
        <br>
        <input type="submit" name="submit" value="S'inscrire">
    </form>

    <script>
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");
        const passwordMatchMessage = document.getElementById("passwordMatchMessage");
        const registrationForm = document.getElementById("registrationForm");

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value) {
                passwordMatchMessage.textContent = "Les mots de passent correspondent.";
                passwordMatchMessage.style.color = "green";
                registrationForm.querySelector("button[type='submit']").removeAttribute("disabled");
            } else {
                passwordMatchMessage.textContent = "Les mots de passe ne correspondent pas.";
                passwordMatchMessage.style.color = "red";
                registrationForm.querySelector("button[type='submit']").setAttribute("disabled", "true");
            }
        }

        password.addEventListener("change", checkPasswordMatch);
        confirmPassword.addEventListener("input", checkPasswordMatch);
    </script>
FORMULAIRE;
        }elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

            try {
                Auth::register($email, $_POST['password'], $_POST['mdpConfirm']);
                $contenu_html .= "<h4>Vous êtes bien inscrit, commencez par créer une playlist !</h4>";
                $contenu_html .= <<<END
<a href="?action=add-playlist">
    <button id="ajouter">Créez votre première playlist</button>
</a>

END;
            } catch (AuthException $e) {
                $erreur = $e->getMessage();
                $contenu_html .= <<<FORMULAIRE
<form id="registrationForm" class="content" action="?action=signup" method="post">
    <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirmPassword">Confirmez le mot de passe :</label>
        <input type="password" id="confirmPassword" name="mdpConfirm" required>
        <p id="passwordMatchMessage"></p>
        <br>
        $erreur
        
        <input type="submit" name="submit" value="S'inscrire">
    </form>

    <script>
        const password = document.getElementById("password");
        const confirmPassword = document.getElementById("confirmPassword");
        const passwordMatchMessage = document.getElementById("passwordMatchMessage");
        const registrationForm = document.getElementById("registrationForm");

        function checkPasswordMatch() {
            if (password.value === confirmPassword.value) {
                passwordMatchMessage.textContent = "Les mots de passent correspondent.";
                passwordMatchMessage.style.color = "green";
                registrationForm.querySelector("button[type='submit']").removeAttribute("disabled");
            } else {
                passwordMatchMessage.textContent = "Les mots de passe ne correspondent pas.";
                passwordMatchMessage.style.color = "red";
                registrationForm.querySelector("button[type='submit']").setAttribute("disabled", "true");
            }
        }

        password.addEventListener("change", checkPasswordMatch);
        confirmPassword.addEventListener("input", checkPasswordMatch);
    </script>
FORMULAIRE;
            }
        }
        return $contenu_html;
    }
}