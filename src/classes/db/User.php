<?php

namespace iutnc\deefy\db;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException;
use PDO;
class User
{
    // Email et mdp de l'utilisateur
    private string $email, $passwd;

    // role de l'utilisateur
    private int $role;

    public function __construct(string $e, string $mdp, int $r = 1){
        $this->email = $e;
        $this->passwd = $mdp;
        $this->role = $r;
    }


    /**
     * Fonction qui renvoie une playlist Playlist à partir de la base de données phpmyadmin (mysql/mariadb)
     * @return array
     */
    public function getPlaylists() : array{
        // requete qui va récupérer les lignes comportant les playlists
        $requete = <<<COUCOU
select id_pl, nom from user
inner join user2playlist on user.id = user2playlist.id_user
inner join playlist on user2playlist.id_pl = playlist.id
where email = ?
COUCOU;

        // On prépare la requête
        $st = ConnectionFactory::$db->prepare($requete);

        // On met l'email de l'utilisateur en condition de la requête
        $st->bindParam(1, $this->email);

        // Puis on exécute la requête
        $st->execute();

        // On prépare notre requête lorsqu'on va fetch toutes les playlists de l'utilisateur
        $requetePrep = <<<END
select * from track inner join playlist2track
on track.id = playlist2track.id_track
where id_pl = ?
END;
        $tabPlaylists = array();

        $pL = ConnectionFactory::$db->prepare($requetePrep);
        // On parcourt le résultat ligne par ligne (des playlists de l'user) pour créer une playlist à chaque fois
        while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
            $playlist = new Playlist($row['nom']);

            // on exécute une nouvelle requête concernant la playlist actuelle du fetch
            $pL->bindParam(1, $row['id_pl']);
            $pL->execute();
            while($musique = $pL->fetch(PDO::FETCH_ASSOC)){
                // On vérifie si c'est un AlbumTrack ou un Podcast
                if($musique['type'] == 'A'){
                    // constructeur : $titre, $chemin, $album = 'anonyme', $numPiste = 0
                    // reste à set : artiste, annee
                    // QUESTION : faut il télécharger la musique (ah bah non le fichier est pas upload sur la bdd
                    $track = new AlbumTrack(
                        $musique['titre'],
                        $musique['filename'],
                        $musique['titre_album'],
                        $musique['numero_album']
                    );
                    try {
                        $track->__set('annee', $musique['annee_album']);
                        $track->__set('artiste', $musique['artiste_album']);
                    } catch (InvalidPropertyNameException|InvalidPropertyValueException|NonEditablePropertyException $e) {
                    }
                }elseif ($musique['type'] == 'P'){
                    // constructeur : $titre, $chemin
                    // reste à set : auteur, annee
                    $track = new PodcastTrack($musique['titre'], $musique['filename']);
                    echo "\nANNEE DU PODCAST (avant convertion) : " . $musique['date_posdcast'];
                    $track->__set('artiste',$musique['auteur_podcast'] );
                    // On prend que l'année de la date du podcast
                    $an = strval($musique['date_posdcast']);
                    echo "\nANNEE DU PODCAST (apres convertion): " . substr($an, 0, 4)."\n";
                    try {
                        $track->__set('annee', substr($an, 0, 4));
                    } catch (InvalidPropertyNameException|InvalidPropertyValueException|NonEditablePropertyException $e) {
                    }
                }
                // on set le reste après puisque c'est la même peu importe AlbumTrack ou PodcastTrack
                // c'est à dire genre et duree
                try {
                    $track->__set('duree', $musique['duree']);
                    $track->__set('genre', $musique['genre']);
                    $playlist->addTrack($track);
                } catch (InvalidPropertyNameException|InvalidPropertyValueException|NonEditablePropertyException $e) {
                }
            }
            $tabPlaylists[] = $playlist;
        }
        return $tabPlaylists;
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