<?php

namespace ChordTrain;

class SongCollection
{
    /**
     * @var Song[]
     */
    private $songs = [];

    private $labels = [];

    public function train($chords, $label)
    {
        $this->songs[] = new Song($label, $chords);
        $this->labels[] = $label;
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
        return array_count_values($this->labels);
    }

    private function getChordCountsInLabels()
    {
        $chordCountsInLabels = [];

        foreach ($this->songs as $song) {
            if (!isset($chordCountsInLabels[$song->label()])) {
                $chordCountsInLabels[$song->label()] = [];
            }
            foreach ($song->chords() as $chord) {
                if ($chordCountsInLabels[$song->label()][$chord] > 0) {
                    $chordCountsInLabels[$song->label()][$chord] = $chordCountsInLabels[$song->label()][$chord] + 1;
                } else {
                    $chordCountsInLabels[$song->label()][$chord] = 1;
                }
            }
        }

        return $chordCountsInLabels;
    }
}
