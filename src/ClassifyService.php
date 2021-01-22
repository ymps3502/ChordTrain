<?php

namespace ChordTrain;

class ClassifyService
{
    public function execute()
    {
        global $song_11;
        global $songs;
        global $labels;
        global $allChords;
        global $labelCounts;
        global $labelProbabilities;
        global $chordCountsInLabels;
        global $probabilityOfChordsInLabels;

        require __DIR__ . './../main.php';

        print_r($labelProbabilities);
        $c1 = $this->classify(['d', 'g', 'e', 'dm'], $labelProbabilities, $probabilityOfChordsInLabels);
        print_r($c1);

        print_r($labelProbabilities);
        $c2 = $this->classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m'], $labelProbabilities, $probabilityOfChordsInLabels);
        print_r($c2);
    }

    private function classify($chords, $labelProbabilities, $probabilityOfChordsInLabels){
        $classified = [];
        foreach (array_keys($labelProbabilities) as $obj) {
            $first = $labelProbabilities[$obj] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $probabilityOfChordsInLabels[$obj][$chord];
                if (!isset($probabilityOfChordInLabel)) {
                    $first + 1.01;
                } else {
                    $first = $first * ($probabilityOfChordInLabel + 1.01);
                }
                $classified[$obj] = $first;
            }
        }
        return $classified;
    }
}
