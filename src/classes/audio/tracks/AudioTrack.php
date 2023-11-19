<?php
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException as InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;
class AudioTrack
{
    /**
     * Déclaration des propriétés
     */
    protected string $titre;
    protected string $artiste;
    protected int $annee;
    protected string $genre;
    protected int $duree = 0; // En secondes
    protected string $nomFich;

    /**
     * Constructeur par défaut
     */
    public function __construct($titre, $chemin)
    {
        $this->titre = $titre;
        $this->nomFich = $chemin;
    }

    public function __toString(): string
    {
        return json_encode($this, JSON_PRETTY_PRINT);
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

    /**
     * @throws NonEditablePropertyException
     * @throws InvalidPropertyValueException
     * @throws InvalidPropertyNameException
     */
    public function __set(string $attr, mixed $value): void
    {
        if (property_exists($this, $attr)) {
            if ($attr === "titre" || $attr === "nomFich") {
                throw new NonEditablePropertyException(get_called_class() . " <non editable property> : $attr");
            } else if ($attr === "duree" && $value < 0) {
                throw new InvalidPropertyValueException(get_called_class() . " <invalid property value> : $attr, $value");
            }
            $this->$attr = $value;
        }else{
            throw new InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");
        }
    }

    /**
     * Permet d'ajouter la track (l'instance elle-même) dans la bdd
     * @return void
     */
    public function insertTrack(): void{
        // On prépare les attributs
        $titre = $this->titre;
        $annee = $this->annee;
        $duree = $this->duree;
        $genre = $this->genre;
        $filename = $this->nomFich;
        $artiste = $this->artiste;

        // Pour savoir quel sera l'id de la musique, faire count des musiques + 1
        //$st = ConnectionFactory::$db->query('SELECT count(id) from track');
        //$id = $st+1;
        // en fait pas besoin car autoincrements id

        // On va devoir utiliser tous les attributs pour les mettre dans la bdd (déjà vérifier
        // si l'instance est PodcastTrack ou AlbumTrack
        if($this instanceof PodcastTrack){
            $type = 'P';
            $date = $annee . '-01-01';
            $requete = <<<END
INSERT INTO TRACK -- pour PodcastTrack
    (titre, genre, duree, filename, type, auteur_podcast, date_posdcast)
VALUES (?, ?, ?, ?, ?, ?, ?);
END;
            $st = ConnectionFactory::$db->prepare($requete);
            $st->bindParam(1, $titre);
            $st->bindParam(2, $genre);
            $st->bindParam(3, $duree);
            $st->bindParam(4, $filename);
            $st->bindParam(5, $type);
            $st->bindParam(6, $artiste);
            $st->bindParam(7, $date);

            $st->execute();
        }elseif($this instanceof AlbumTrack){
            $type = 'A';
            // Pour pouvoir accéer a album et numeroPiste, redéfinir le getter dans AlbumTrack
            $titre_album = $this->album;
            $numero_album = $this->numeroPiste;
            $requete = <<<END
INSERT INTO TRACK
    (titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
END;
            $st = ConnectionFactory::$db->prepare($requete);
            $st->bindParam(1, $titre);
            $st->bindParam(2, $genre);
            $st->bindParam(3, $duree);
            $st->bindParam(4, $filename);
            $st->bindParam(5, $type);
            $st->bindParam(6, $artiste);
            $st->bindParam(7, $titre_album);
            $st->bindParam(8, $annee);
            $st->bindParam(9, $numero_album);
            $st->execute();
        }
    }
}