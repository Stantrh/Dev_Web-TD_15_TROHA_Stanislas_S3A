<?php

namespace iutnc\deefy\action;

class DefaultAction extends Action
{

    public function execute(): string
    {
        $contenu_html = "<h4>Bienvenue dans Deefy ! \n</h4>";
        $contenu_html .= "<h4>La plateforme de streaming de musique n°1</h4>";

        return $contenu_html;
    }
}