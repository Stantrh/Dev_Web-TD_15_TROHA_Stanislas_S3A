<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\SignOutAction;
use iutnc\deefy\exception\AuthException;

class Dispatcher
{

    private string $action;


    public function __construct(){
        if(!isset($_GET['action']))
            $_GET['action'] = 'default';
        $this->action = $_GET['action'];

    }

    public function run(){
        switch($this->action){
            case 'display-playlist':{
                $displayplay = new DisplayPlaylistAction();
                $html = $displayplay->execute();
                self::renderPage($html);
                break;
            }
            case 'add-playlist':{
                $addPlay = new AddPlaylistAction();
                $html = $addPlay->execute();
                self::renderPage($html);
                break;
            }
            case 'add-podcasttrack':{
                $addPod = new AddPodcastTrackAction();
                $html = $addPod->execute();
                self::renderPage($html);
                break;
            }
            case 'signin':{
                $signin = new SigninAction();
                try {
                    $html = $signin->execute();
                    self::renderPage($html);
                }catch (AuthException $e) {
                }

                break;
            }
            case 'signup':{
                $signup = new AddUserAction();
                $html = $signup->execute();
                self::renderPage($html);
                break;
            }
            case 'signout':{
                $signout = new SignOutAction();
                $html = $signout->execute();
                self::renderPage($html);
                break;
            }
            default:{
                $default = new DefaultAction();
                $html = $default->execute();
                self::renderPage($html);
                break;
            }
        }
    }


    private function renderPage(string $contenu_html){
        $res = <<<END
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deefy - Dev Web</title>
</head>
<body>
    <header>
        <h1>Deefy streaming</h1>
        <nav>
            <ul>
                <li><a href="?action=default">Accueil</a></li>
                <li><a href="?action=add-playlist">Créer une playlist</a></li>
END;
if(isset($_SESSION['user'])) {
    $res .= <<<END
<li><a href="?action=signout">Se deconnecter</a></li>
END;
}else {
    $res .= <<<END
<li><a href="?action=signup">Inscription</a></li>
<li><a href ="?action=signin">Se connecter</a></li>
END;
}
$res .= <<<END
            </ul>
        </nav>
    </header>
    
    <body>
        $contenu_html
    </body>
    <style>
    body {
            font-family: Arial, sans-serif;
            background-color: #A9EAFE;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #74d0f1;
            color: #ffffff;
            text-align: center;
            padding: 20px 0;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            text-align: center;
        }
        nav ul li {
            display: inline;
            margin-right: 20px;
        }
        nav ul li a {
            text-decoration: none;
            color: #18497e;
        }
        nav ul li a:hover {
            border-bottom: 2px solid #fff;  
        }
        h4 {
            text-align: center;
            color: white;
        }
        h3 {
            text-align: center;
        }
        #error{
            color: #d91932;
        }
        .content form {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
    }
    
    .content form label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
    }
    
    .content form input[type="text"],
    .content form input[type="number"],
    .content form input[type="file"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f4f4f4;
    }
    
    .content form input[type="submit"] {
        background-color: #337ab7;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .content form input[type="submit"]:hover {
        background-color: #235a94;
    }
    
        #decouvrir {
            display: block;
            margin: 0 auto;
        }
        #decouverte{
            max-width: 100px;
        }
        form {
            margin: 0 auto;
            max-width: 500px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
        }
        form label {
            display: block;
            margin-bottom: 10px;
        }
        form input{
            width: 90%; 
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px; 
        }
        form input[type="submit"] {
            background-color:  #acc8ea;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            align-content: center;
        }
        form input[type="submit"]:hover {
            background-color: #3535d9;
        }
        .album-track {
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }
    
    .album-track p {
        margin-bottom: 10px;
    }
    
    .album-track audio {
        width: 100%;
    }
    
    .audio-list {
    padding: 15px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    }
    
    .audio-list li {
        margin-bottom: 5px;
    }
    
    .podcast {
        padding: 10px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
    }
    
    .podcast p {
        margin-bottom: 10px;
    }
    
    .podcast video {
        width: 100%;
    }
    #ajouter {
    display: inline-block;
    padding: 10px 20px;
    text-decoration: none;
    background-color: #337ab7;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    }
    
    #ajouter:hover {
        background-color: #235a94;
    }


    </style>
</body>
</html>
END;
        echo $res;
    }
}