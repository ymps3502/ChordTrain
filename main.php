<?php

$song_11 = [];
$songs = [];
$labels = [];
$allChords = [];
$labelCounts = [];
$labelProbabilities = [];
$chordCountsInLabels = [];
$probabilityOfChordsInLabels = [];

function train($chords, $label)
{
    $GLOBALS['songs'][] = [$label, $chords];
    $GLOBALS['label'][] = $label;
    for ($i = 0; $i < count($chords); $i++) {
        if (!in_array($chords[$i], $GLOBALS['allChords'])) {
            $GLOBALS['allChords'][] = $chords[$i];
        }
    }
    if (!!(in_array($label, array_keys($GLOBALS['labelCounts'])))) {
        $GLOBALS['labelCounts'][$label] = $GLOBALS['labelCounts'][$label] + 1;
    } else {
        $GLOBALS['labelCounts'][$label] = 1;
    }
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
    foreach ($GLOBALS['songs'] as $i) {
        if (!isset($GLOBALS['chordCountsInLabels'][$i[0]])) {
            $GLOBALS['chordCountsInLabels'][$i[0]] = [];
        }
        foreach ($i[1] as $j) {
            if ($GLOBALS['chordCountsInLabels'][$i[0]][$j] > 0) {
                $GLOBALS['chordCountsInLabels'][$i[0]][$j] = $GLOBALS['chordCountsInLabels'][$i[0]][$j] + 1;
            } else {
                $GLOBALS['chordCountsInLabels'][$i[0]][$j] = 1;
            }
        }
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
