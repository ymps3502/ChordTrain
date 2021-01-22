<?php

namespace ChordTrain;

class Classify
{
    private $labelProbabilities = [];

    private $probabilityOfChordsInLabels = [];

    public function __construct($labelProbabilities, $probabilityOfChordsInLabels)
    {
        $this->labelProbabilities = $labelProbabilities;
        $this->probabilityOfChordsInLabels = $probabilityOfChordsInLabels;
    }

    public function classify($chords){
        $classified = [];
        foreach (array_keys($this->labelProbabilities) as $obj) {
            $first = $this->labelProbabilities[$obj] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probabilityOfChordsInLabels[$obj][$chord];
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
