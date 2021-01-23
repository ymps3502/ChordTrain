<?php

namespace ChordTrain;

class Song
{
    private $label;
    private $chords;

    public function __construct($label, $chords)
    {
        $this->label = $label;
        $this->chords = $chords;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function chords(): array
    {
        return $this->chords;
    }
}
