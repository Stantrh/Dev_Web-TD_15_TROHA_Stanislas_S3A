<?php
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class Playlist extends AudioList
{


    /**
     * @throws InvalidPropertyNameException
     */
    public function ajouterPiste(\iutnc\deefy\audio\tracks\AudioTrack $piste): void
    {
        if (!in_array($piste, $this->tab)) {
            $this->tab[] = $piste;
            $this->duree += $piste->__get('duree');
            $this->nbPistes++;
        }

    }

    public function supprimerPiste(int $indice): void
    {
        $this->duree -= $this->tab[$indice]->duree;
        $this->nbPistes--;
        unset($this->tab[$indice]);
    }

    /**
     * Pour éviter les doublons on peut utiliser in_array
     */
    public function ajouterListe(array $liste): void
    {
        foreach ($liste as $piste) {
            if (!in_array($piste, $this->tab)) {
                self::ajouterListe($piste);
            }
        }
    }

    /**
     * Méthode qui retourne le résultat (tableau d'AudioTrack) de la requête SQL permettant
     * d'avoir les listes de la playlist courante (on doit pouvoir ajouter cette liste
     * @return array
     */
    public function getTrackList() : array{
        // récupère les track de la playlist
        $requete = <<<END
select * from playlist inner join playlist2track on playlist.id = playlist2track.id_pl
         inner join track on playlist2track.id_track = track.id
         where nom = ?
END;
        // On prépare la requête
        $st = ConnectionFactory::$db->prepare($requete);


        // On ajoute le paramètre (nom de la playlist)
        $st->bindParam(1, $this->nom);

        $st->execute();


        // on parcourt les colonnes (donc les track associées à la playlist
        while($row = $st->fetch(\PDO::FETCH_ASSOC)){
            if($row['type'] === 'A'){
                // constructeur : ($titre, $chemin, $album = 'anonyme', $numPiste = 0)
                $track = new AlbumTrack($row['titre'], $row['filename'], $this->nom, $row['numero_album']);
                // annee, genre, duree, artiste
                $track->__set('annee', $row['annee_album']);
                $track->__set('duree', $row['duree']);
                $track->__set('genre', $row['genre']);
                $track->__set('artiste', $row['artiste_album']);
            }elseif($row['type'] === 'P'){
                $track = new PodcastTrack($row['titre'], $row['filename']);
                //    protected string $artiste;
                //    protected string $genre;
                //    protected int $duree = 0; // En secondes
                $an = strval($row['date_posdcast']);
                $track->__set('annee', substr($an, 0, 4));
                $track->__set('artiste', $row['auteur_podcast']);
                $track->__set('genre', $row['genre']);
                $track->__set('duree', $row['duree']);
            }
            $this->addTrack($track);
        }
        return $this->tab;
    }

    /**
     * Méthode qui à partir de l'indentifiant d'une playlist
     * @param int $id
     * @return Playlist
     */
    public static function find(int $id) : Playlist{
        /**
         * On prépare la requête
         */
        $requete = "select * from playlist inner join playlist2track on playlist.id = playlist2track.id_pl
         inner join track on playlist2track.id_track where playlist.id = ?";
        $resultSet = ConnectionFactory::$db->prepare($requete);

        $resultSet->bindParam(1, $id);

        $resultSet->execute();

        // On construit un objet Playlist correspondant à la playlist qu'on va renvoyer
        $nom = $resultSet->fetch(\PDO::FETCH_ASSOC)['nom'];
        $p = new Playlist($nom);

        // Puis maintenant qu'on a un objet playlist, on peut utiliser getTrackList
        $list = $p->getTrackList();

        // Puis on ajoute toutes les pistes à la playlist
        foreach ($list as $track) {
            try {
                $p->addTrack($track);
            } catch (InvalidPropertyNameException|InvalidPropertyValueException $e) {
            }
        }
        return $p;
    }
}