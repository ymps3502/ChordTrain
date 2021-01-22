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
        $this->trainSongs();

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

    private function trainSongs(): void
    {
        // songs
        $imagine = ['c', 'cmaj7', 'f', 'am', 'dm', 'g', 'e7'];
        $somewhere_over_the_rainbow = ['c', 'em', 'f', 'g', 'am'];
        $tooManyCooks = ['c', 'g', 'f'];
        $iWillFollowYouIntoTheDark = ['f', 'dm', 'bb', 'c', 'a', 'bbm'];
        $babyOneMoreTime = ['cm', 'g', 'bb', 'eb', 'fm', 'ab'];
        $creep = ['g', 'gsus4', 'b', 'bsus4', 'c', 'cmsus4', 'cm6'];
        $army = ['ab', 'ebm7', 'dbadd9', 'fm7', 'bbm', 'abmaj7', 'ebm'];
        $paperBag = ['bm7', 'e', 'c', 'g', 'b7', 'f', 'em', 'a', 'cmaj7', 'em7', 'a7', 'f7', 'b'];
        $toxic = ['cm', 'eb', 'g', 'cdim', 'eb7', 'd7', 'db7', 'ab', 'gmaj7', 'g7'];
        $bulletproof = ['d#m', 'g#', 'b', 'f#', 'g#m', 'c#'];
        train($imagine, 'easy');
        train($somewhere_over_the_rainbow, 'easy');
        train($tooManyCooks, 'easy');
        train($iWillFollowYouIntoTheDark, 'medium');
        train($babyOneMoreTime, 'medium');
        train($creep, 'medium');
        train($paperBag, 'hard');
        train($toxic, 'hard');
        train($bulletproof, 'hard');

        setLabelProbabilities();
        setChordCountsInLabels();
        setProbabilityOfChordsInLabels();
    }
}
