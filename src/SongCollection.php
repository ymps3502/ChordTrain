<?php

namespace ChordTrain;

class SongCollection
{
    /**
     * @var Song[]
     */
    private $songs = [];

    private $labels = [];

    public function getNumberOfSongs()
    {
        return count($this->songs);
    }

    public function train($chords, $label)
    {
        $this->songs[] = new Song($label, $chords);
        $this->labels[] = $label;
    }

    public function labelCounts()
    {
        return array_count_values($this->labels);
    }

    public function toArray()
    {
        return array_map(function (Song $song) {
            return $song->toArray();
        }, $this->songs);
    }
}