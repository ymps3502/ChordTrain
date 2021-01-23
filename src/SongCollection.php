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

    function getChordCountsInLabels()
    {
        $songs = $this->toArray();

        $chordCountsInLabels = [];

        foreach ($songs as $i) {
            if (!isset($chordCountsInLabels[$i[0]])) {
                $chordCountsInLabels[$i[0]] = [];
            }
            foreach ($i[1] as $j) {
                if ($chordCountsInLabels[$i[0]][$j] > 0) {
                    $chordCountsInLabels[$i[0]][$j] = $chordCountsInLabels[$i[0]][$j] + 1;
                } else {
                    $chordCountsInLabels[$i[0]][$j] = 1;
                }
            }
        }

        return $chordCountsInLabels;
    }
}