<?php

namespace ChordTrain;

class ClassifyService
{
    /** @var SongCollection */
    private $songCollection;

    public function __construct()
    {
        $this->songCollection = new SongCollection();
    }

    public function execute()
    {
        $this->trainSongs();

        $labelCounts = $this->songCollection->labelCounts();

        $labelProbabilities = $this->songCollection->getLabelProbabilities($labelCounts, $this->songCollection->getNumberOfSongs());

        $chordCountsInLabels = $this->songCollection->getChordCountsInLabels();
        $probabilityOfChordsInLabels = $this->songCollection->getProbabilityOfChordsInLabels($chordCountsInLabels);

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
        $this->songCollection->train($imagine, 'easy');
        $this->songCollection->train($somewhere_over_the_rainbow, 'easy');
        $this->songCollection->train($tooManyCooks, 'easy');
        $this->songCollection->train($iWillFollowYouIntoTheDark, 'medium');
        $this->songCollection->train($babyOneMoreTime, 'medium');
        $this->songCollection->train($creep, 'medium');
        $this->songCollection->train($paperBag, 'hard');
        $this->songCollection->train($toxic, 'hard');
        $this->songCollection->train($bulletproof, 'hard');
    }
}
