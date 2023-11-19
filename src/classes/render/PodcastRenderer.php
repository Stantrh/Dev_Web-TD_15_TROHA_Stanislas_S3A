<?php
namespace iutnc\deefy\render;

class PodcastRenderer extends AudioTrackRenderer
{

    /**
     * Redéfinition du constructeur pour forcer le type (podcast only)
     */
    public function __construct(\iutnc\deefy\audio\tracks\PodcastTrack $p)
    {
        parent::__construct($p);
    }
    /**
     * fonction courte de rendu
     */
    public function short(): string
    {
        $html = "<div class='podcast'>";
        $html .= "<p>{$this->track->titre}</p>";
        $html .= "<video controls><source src={$this->track->nomFich} type=video/mp4></video>";
        $html .= "</div>";
        return $html;
    }

    /**
     * fonction longue de rendu
     */
    public function long(): string
    {
        $html = "<div class='podcast'>";
        $html .= "<li>{$this->track->titre}</li>";
        $html .= "<li>{$this->track->duree} secondes</li>";
        $html .= "<li><audio controls><source src={$this->track->nomFich} type=audio/mp3></audio></li>";
        $html .= "</div>";
        return $html;
    }
}