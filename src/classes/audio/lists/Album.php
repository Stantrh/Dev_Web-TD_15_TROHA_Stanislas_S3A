<?php
namespace iutnc\deefy\audio\lists;

class Album extends AudioList
{
    /**
     * Attributs supplémentaires
     */
    protected string $artiste;

    protected string $date;

    public function __set(string $attr, string $value): void
    {
        if (property_exists($this, $attr)) {
            if ($attr === 'artiste' || $attr === 'date') {
                $this->$attr = $value;
            } else {
                throw new \iutnc\deefy\exception\InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");
            }
        }
        throw new \iutnc\deefy\exception\InvalidPropertyNameException(get_called_class() . " invalid property : <$attr>");
    }
}