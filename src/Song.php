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

    public function toArray()
    {
        return [$this->label, $this->chords];
    }
}
