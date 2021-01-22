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
    }
}
