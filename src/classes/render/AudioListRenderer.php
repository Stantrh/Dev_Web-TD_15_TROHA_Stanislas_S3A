<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList as AudioList;

use iutnc\deefy\audio\tracks\AlbumTrack as AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack as PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioListRenderer implements Renderer
{
    protected AudioList $liste;

    protected string $rendered = "";
    public function __construct(AudioList $liste)
    {
        $this->liste = $liste;


    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function render(): string
    {
        $res = "<div class='audio-list'>";
        $res .= "<h1>Nom de la playlist : {$this->liste->__get('nom')}</h1>";
        $res .= "<ul>";
        foreach ($this->liste->__get('tab') as $piste) {
            $renderer = null;
            if ($piste instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($piste);
            } else if ($piste instanceof PodcastTrack) {
                $renderer = new PodcastRenderer($piste);
            }
            $aff = $renderer->long();
            $res .= $aff;
        }
        $res .= "</ul>";
        $res .= "<p>Nombre de pistes : {$this->liste->__get('nbPistes')} " .
            " pour une durée totale de : {$this->liste->__get('duree')} secondes</p>";
        $res .= "</div>";
        $this->rendered = $res;
        return $this->rendered;
    }


}