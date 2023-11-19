<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioList
{

    /**
     * attributs
     */
    protected string $nom;
    protected int $nbPistes;
    protected int $duree;
    protected array $tab;

    public function __construct(string $nomListe, array $tab = [])
    {
        $this->nom = $nomListe;
        $this->tab = $tab;
        // variable pour la duree
        $duree_totale = 0;
        foreach ($tab as $p) {
            $duree_totale += $p->__get('duree');
        }
        $this->duree = $duree_totale;
        $this->nbPistes = count($tab);
    }

    /**
     * @throws InvalidPropertyNameException
     */
    public function __get(string $attr): mixed
    {
        if (property_exists($this, $attr)) {
            return $this->$attr;
        }
        throw new \iutnc\deefy\exception\InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");
    }

    /**
     * @throws InvalidPropertyValueException
     * @throws InvalidPropertyNameException
     */
    public function addTrack($track){
        if($track instanceof AudioTrack){
            $this->tab[] = $track;
            $this->duree += $track->__get('duree');
            $this->nbPistes += 1;

        }else{
            throw new InvalidPropertyValueException(get_called_class() . " invalid property value : <$track>");
        }
    }
}