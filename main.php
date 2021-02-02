<?php

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
$song_11 = [];
$songs = [];
$labels = [];
$allChords = [];
$labelCounts = [];
$labelProbabilities = [];
$chordCountsInLabels = [];
$probabilityOfChordsInLabels = [];

class Song
{
    public $chords;
    public $label;

    public function __construct(array $chords, string $label)
    {
        $this->chords = $chords;
        $this->label = $label;
    }
}

function train($chords, $label)
{
    $GLOBALS['songs'][] = new Song($chords, $label);
    $GLOBALS['label'][] = $label;
    foreach ($chords as $chord) {
        if (!in_array($chord, $GLOBALS['allChords'] ?? [])) {
            $GLOBALS['allChords'][] = $chord;
        }
    }
    // init $GLOBALS['labelCounts'][$label]
    $GLOBALS['labelCounts'][$label] = $GLOBALS['labelCounts'][$label] ?? 0;
    $GLOBALS['labelCounts'][$label]++;
}

function getNumberOfSongs()
{
    return count($GLOBALS['songs']);
}

function setLabelProbabilities()
{
    foreach (array_keys($GLOBALS['labelCounts']) as $label) {
        $numberOfSongs = getNumberOfSongs();
        $GLOBALS['labelProbabilities'][$label] = $GLOBALS['labelCounts'][$label] / $numberOfSongs;
    }
}

function setChordCountsInLabels()
{
    /** @var Song $song */
    foreach ($GLOBALS['songs'] as $song) {
        $label = $song->label;
        $chordCountsInLabels = $GLOBALS['chordCountsInLabels'][$label] ?? [];
        foreach ($song->chords as $chord) {
            $chordCountsInLabels[$chord] = $chordCountsInLabels[$chord] ?? 0;
            $chordCountsInLabels[$chord]++;
        }
        $GLOBALS['chordCountsInLabels'][$label] = $chordCountsInLabels;
    }
}

function setProbabilityOfChordsInLabels()
{
    $GLOBALS['probabilityOfChordsInLabels'] = $GLOBALS['chordCountsInLabels'];
    foreach (array_keys($GLOBALS['probabilityOfChordsInLabels']) as $i) {
        foreach (array_keys($GLOBALS['probabilityOfChordsInLabels'][$i]) as $j) {
            $GLOBALS['probabilityOfChordsInLabels'][$i][$j] = $GLOBALS['probabilityOfChordsInLabels'][$i][$j] * 1.0 / count($GLOBALS['songs']);
        }
    }
}

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

function classify($chords){
    $ttal = $GLOBALS['labelProbabilities'];
    print_r($ttal);
    $classified = [];
    foreach (array_keys($ttal) as $obj) {
        $first = $GLOBALS['labelProbabilities'][$obj] + 1.01;
        foreach ($chords as $chord) {
            $probabilityOfChordInLabel = $GLOBALS['probabilityOfChordsInLabels'][$obj][$chord] ?? 0;
            if (empty($probabilityOfChordInLabel)) {
                $first + 1.01;
            } else {
                $first = $first * ($probabilityOfChordInLabel + 1.01);
            }
            $classified[$obj] = $first;
        }
    }
    print_r($classified);
}

classify(['d', 'g', 'e', 'dm']);
classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']);