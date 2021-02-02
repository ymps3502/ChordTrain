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
        foreach (array_keys($this->labelProbabilities) as $label) {
            $first = $this->labelProbabilities[$label] + 1.01;
            foreach ($chords as $chord) {
                $probabilityOfChordInLabel = $this->probabilityOfChordsInLabels[$label][$chord];
                if (isset($probabilityOfChordInLabel)) {
                    $first = $first * ($probabilityOfChordInLabel + 1.01);
                }
                $classified[$label] = $first;
            }
        }
        return $classified;
    }
}
