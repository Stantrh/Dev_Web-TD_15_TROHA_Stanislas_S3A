<?php
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException as InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException as InvalidPropertyValueException;
use iutnc\deefy\exception\NonEditablePropertyException as NonEditablePropertyException;

class PodcastTrack extends AudioTrack
{

    public function __construct($titre, $chemin)
    {
        parent::__construct($titre, $chemin);
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