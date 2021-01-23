<?php

namespace ChordTrain;

class SongCollection
{
    /**
     * @var Song[]
     */
    private $songs = [];

    private $labels = [];

    private function getNumberOfSongs()
    {
        return count($this->songs);
    }

    public function train($chords, $label)
    {
        $this->songs[] = new Song($label, $chords);
        $this->labels[] = $label;
    }

    private function labelCounts()
    {
        return array_count_values($this->labels);
    }

    private function toArray()
    {
        return array_map(function (Song $song) {
            return $song->toArray();
        }, $this->songs);
    }

    private function getChordCountsInLabels()
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
        foreach (array_keys($probabilityOfChordsInLabels) as $i) {
            foreach (array_keys($probabilityOfChordsInLabels[$i]) as $j) {
                $probabilityOfChordsInLabels[$i][$j] = $probabilityOfChordsInLabels[$i][$j] * 1.0 / $this->getNumberOfSongs();
            }
        }
        return $probabilityOfChordsInLabels;
    }
}