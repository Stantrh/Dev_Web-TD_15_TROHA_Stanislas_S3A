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

}