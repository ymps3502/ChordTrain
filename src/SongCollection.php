<?php

namespace ChordTrain;

use Illuminate\Support\Collection;

class SongCollection
{
    /**
     * @var Song[]
     */
    private $songs;

    public function __construct()
    {
        $this->songs = new Collection();
    }

    public function train($chords, $label)
    {
        $this->songs[] = new Song($label, $chords);
    }

    public function getLabelProbabilities()
    {
        $labelCounts = $this->labelCounts();

        $labelProbabilities = [];
        foreach (array_keys($labelCounts) as $label) {
            $labelProbabilities[$label] = $labelCounts[$label] / $this->getNumberOfSongs();
        }

        return $labelProbabilities;
    }

    public function getProbabilityOfChordsInLabels()
    {
        $chordCountsInLabels = $this->getChordCountsInLabels();

        $probabilityOfChordsInLabels = $chordCountsInLabels;
        foreach (array_keys($probabilityOfChordsInLabels) as $label) {
            foreach (array_keys($probabilityOfChordsInLabels[$label]) as $chord) {
                $probabilityOfChordsInLabels[$label][$chord] = $probabilityOfChordsInLabels[$label][$chord] * 1.0 / $this->getNumberOfSongs();
            }
        }
        return $probabilityOfChordsInLabels;
    }

    private function getNumberOfSongs()
    {
        return count($this->songs);
    }

    private function labelCounts()
    {
        $allLabels = $this->songs->map(function (Song $song) {
            return $song->label();
        });

        return array_count_values($allLabels->toArray());
    }

    private function getChordCountsInLabels()
    {
        $chordCountsInLabels = [];

        $labels = $this->songs->map(function (Song $song) {
            return $song->label();
        })->unique();

        foreach ($labels as $label) {
            $chords = $this->getAllChordsByLabel($label);
            $chordCountsInLabels[$label] = array_count_values($chords);
        }

        return $chordCountsInLabels;
    }

    private function getAllChordsByLabel($label): array
    {
        $allChords = [];
        foreach ($this->songs as $song) {
            if ($song->label() != $label) {
                continue;
            }

            array_push($allChords, ... $song->chords());
        }

        return $allChords;
    }
}
