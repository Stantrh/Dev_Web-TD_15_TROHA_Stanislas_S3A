<?php
namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack as AudioTrack;

abstract class AudioTrackRenderer
{

    protected AudioTrack $track;
    protected string $rendered;

    public function __construct(AudioTrack $aT)
    {
        $this->track = $aT;
    }

    /**
     * fonction de rendu
     */
    public function render(int $selector): string
    {
        $html = "";
        switch ($selector) {
            case Renderer::SHORT: {
                    $html = $this->short();
                    break;
                }
            case Renderer::COMPACT: {
                    $html = $this->long();
                    break;
                }
        }
        $this->rendered = $html;
        return $html;
    }

    public abstract function short(): string;

    public abstract function long(): string;
}