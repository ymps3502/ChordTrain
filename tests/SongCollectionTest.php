<?php

namespace Tests;

use ChordTrain\SongCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers
 */
class SongCollectionTest extends TestCase
{
    /**
     * @testdox 取得各難易度權重
     */
    public function testGetLabelProbabilities()
    {
        $songCollection = new SongCollection();

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
        $songCollection->train($imagine, 'easy');
        $songCollection->train($somewhere_over_the_rainbow, 'easy');
        $songCollection->train($tooManyCooks, 'easy');
        $songCollection->train($iWillFollowYouIntoTheDark, 'medium');
        $songCollection->train($babyOneMoreTime, 'medium');
        $songCollection->train($creep, 'medium');
        $songCollection->train($paperBag, 'hard');
        $songCollection->train($toxic, 'hard');
        $songCollection->train($bulletproof, 'hard');

        $labelProbabilities = $songCollection->getLabelProbabilities();

        $this->assertEquals([
            'easy' => 0.33333333333333,
            'medium' => 0.33333333333333,
            'hard' => 0.33333333333333,
        ], $labelProbabilities);
    }
}