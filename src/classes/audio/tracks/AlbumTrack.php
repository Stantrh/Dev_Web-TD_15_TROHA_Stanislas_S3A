<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;

class AlbumTrack extends AudioTrack
{

    /**
     * Déclaration des propriétés
     */
    private string $album; // titre de l'album
    private int $numeroPiste; // Dans l'album du son, pas de la playlist

    /**
     * Constructeur par défaut
     */
    public function __construct($titre, $chemin, $album = 'anonyme', $numPiste = 0)
    {
        parent::__construct($titre, $chemin);
        $this->album = $album;
        $this->numeroPiste = $numPiste;
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $attr): mixed
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        throw new InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");

    }
}