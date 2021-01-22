<?php

namespace ChordTrain;

class ClassifyService
{
    private $songs = [];
    private $allChords = [];
    private $labels = [];
    private $labelCounts = [];
    private $labelProbabilities = [];
    private $chordCountsInLabels = [];
    private $probabilityOfChordsInLabels = [];

    public function execute()
    {
        $this->trainSongs();

        return [$this->labelProbabilities, $this->probabilityOfChordsInLabels];
    }

    private function trainSongs(): void
    {
        // songs
        $imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
        $somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
        $tooManyCooks = ['c', 'g', 'f'];
        $iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
        $babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
        $creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
        $paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
        $toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
        $bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];
        $this->train($imagine, 'easy');
        $this->train($somewhere_over_the_rainbow, 'easy');
        $this->train($tooManyCooks, 'easy');
        $this->train($iWillFollowYouIntoTheDark, 'medium');
        $this->train($babyOneMoreTime, 'medium');
        $this->train($creep, 'medium');
        $this->train($paperBag, 'hard');
        $this->train($toxic, 'hard');
        $this->train($bulletproof, 'hard');

        $this->setLabelProbabilities();
        $this->setChordCountsInLabels();
        $this->setProbabilityOfChordsInLabels();
    }

    function train($chords, $label)
    {
        $this->songs[] = [$label, $chords];
        $this->labels[] = $label;
        for ($i = 0; $i < count($chords); $i++) {
            if (!in_array($chords[$i], $this->allChords)) {
                $this->allChords[] = $chords[$i];
            }
        }
        if (!!(in_array($label, array_keys($this->labelCounts)))) {
            $this->labelCounts[$label] = $this->labelCounts[$label] + 1;
        } else {
            $this->labelCounts[$label] = 1;
        }
    }

    function getNumberOfSongs()
    {
        return count($this->songs);
    }

    function setLabelProbabilities()
    {
        foreach (array_keys($this->labelCounts) as $label) {
            $numberOfSongs = $this->getNumberOfSongs();
            $this->labelProbabilities[$label] = $this->labelCounts[$label] / $numberOfSongs;
        }
    }

    function setChordCountsInLabels()
    {
        foreach ($this->songs as $i) {
            if (!isset($this->chordCountsInLabels[$i[0]])) {
                $this->chordCountsInLabels[$i[0]] = [];
            }
            foreach ($i[1] as $j) {
                if ($this->chordCountsInLabels[$i[0]][$j] > 0) {
                    $this->chordCountsInLabels[$i[0]][$j] = $this->chordCountsInLabels[$i[0]][$j] + 1;
                } else {
                    $this->chordCountsInLabels[$i[0]][$j] = 1;
                }
            }
        }
    }

    function setProbabilityOfChordsInLabels()
    {
        $this->probabilityOfChordsInLabels = $this->chordCountsInLabels;
        foreach (array_keys($this->probabilityOfChordsInLabels) as $i) {
            foreach (array_keys($this->probabilityOfChordsInLabels[$i]) as $j) {
                $this->probabilityOfChordsInLabels[$i][$j] = $this->probabilityOfChordsInLabels[$i][$j] * 1.0 / count($this->songs);
            }
        }
    }
}
