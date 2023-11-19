<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack as AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer
{

    /**
     * Redéfinition du constructeur pour forcer le type (être sur de recevoir un album)
     */
    public function __construct(AlbumTrack $a)
    {
        parent::__construct($a);
    }

    /**
     * fonction courte de rendu
     */
    public function short(): string
    {
        $html = "<div class='album-track'>";
        $html .= "<p>{$this->track->titre}</p>";
        $html .= "<audio controls src={$this->track->nomFich}></audio>";
        $html .= "</div>";
        return $html;
    }

    /**
     * fonction longue de rendu
     */
    public function long(): string
    {
        $html = "<div class='album-track'>";
        $html .= "<li>{$this->track->titre}</li>";
        $html .= "<li>{$this->track->duree} secondes</li>";
        $html .= "<li><audio controls src={$this->track->nomFich}></audio></li>";
        $html .= "</div>";
        return $html;
    }
}