<?php

namespace ChordTrain;

class ClassifyService
{
    private $songs = [];
    private $allChords = [];
    private $labels = [];
    private $labelCounts = [];

    public function execute()
    {
        $this->trainSongs();

        $labelProbabilities = $this->getLabelProbabilities($this->labelCounts, $this->getNumberOfSongs());
        $chordCountsInLabels = $this->getChordCountsInLabels($this->songs);
        $probabilityOfChordsInLabels = $this->getProbabilityOfChordsInLabels($chordCountsInLabels);

        return [$labelProbabilities, $probabilityOfChordsInLabels];
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

    function getLabelProbabilities($labelCounts, $numberOfSongs)
    {
        $labelProbabilities = [];
        foreach (array_keys($labelCounts) as $label) {
            $labelProbabilities[$label] = $labelCounts[$label] / $numberOfSongs;
        }

        return $labelProbabilities;
    }

    function getChordCountsInLabels($songs)
    {
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

    function getProbabilityOfChordsInLabels($chordCountsInLabels)
    {
        $probabilityOfChordsInLabels = $chordCountsInLabels;
        foreach (array_keys($probabilityOfChordsInLabels) as $i) {
            foreach (array_keys($probabilityOfChordsInLabels[$i]) as $j) {
                $probabilityOfChordsInLabels[$i][$j] = $probabilityOfChordsInLabels[$i][$j] * 1.0 / count($this->songs);
            }
        }
        return $probabilityOfChordsInLabels;
    }
}
