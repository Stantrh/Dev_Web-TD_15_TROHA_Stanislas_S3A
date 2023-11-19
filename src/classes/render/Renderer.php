<?php
namespace iutnc\deefy\render;

interface Renderer
{
    const SHORT = 1;
    const COMPACT = 2;

    public function render(): string;
}