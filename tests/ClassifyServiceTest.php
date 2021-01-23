<?php

namespace Tests;

use ChordTrain\Classify;
use ChordTrain\ClassifyService;
use ChordTrain\SongCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers
 */
class ClassifyServiceTest extends TestCase
{
    public function testCanClassify()
    {
        $this->expectOutputString("Array
(
    [easy] => 0.33333333333333
    [medium] => 0.33333333333333
    [hard] => 0.33333333333333
)
Array
(
    [easy] => 2.0230948271605
    [medium] => 1.8557586131687
    [hard] => 1.8557586131687
)
Array
(
    [easy] => 0.33333333333333
    [medium] => 0.33333333333333
    [hard] => 0.33333333333333
)
Array
(
    [easy] => 1.3433333333333
    [medium] => 1.5060259259259
    [hard] => 1.688422399177
)
");

        $service = new ClassifyService();

        [$labelProbabilities, $probabilityOfChordsInLabels] = $service->execute();

        $classify = new Classify($labelProbabilities, $probabilityOfChordsInLabels);

        print_r($labelProbabilities);
        $c1 = $classify->classify(['d', 'g', 'e', 'dm']);
        print_r($c1);

        print_r($labelProbabilities);
        $c2 = $classify->classify(['f#m7', 'a', 'dadd9', 'dmaj7', 'bm', 'bm7', 'd', 'f#m']);
        print_r($c2);
    }

    public function testExecute()
    {
        $service = new ClassifyService();

        $this->assertEquals([
            [
                'easy' => 0.33333333333333,
                'medium' => 0.33333333333333,
                'hard' => 0.33333333333333,
            ],
            [
                'easy' => [
                    'c' => 0.33333333333333,
                    'cmaj7' => 0.11111111111111,
                    'f' => 0.33333333333333,
                    'am' => 0.22222222222222,
                    'dm' => 0.11111111111111,
                    'g' => 0.33333333333333,
                    'e7' => 0.11111111111111,
                    'em' => 0.11111111111111,
                ],
                'medium' => [
                    'f' => 0.11111111111111,
                    'dm' => 0.11111111111111,
                    'bb' => 0.22222222222222,
                    'c' => 0.22222222222222,
                    'a' => 0.11111111111111,
                    'bbm' => 0.11111111111111,
                    'cm' => 0.11111111111111,
                    'g' => 0.22222222222222,
                    'eb' => 0.11111111111111,
                    'fm' => 0.11111111111111,
                    'ab' => 0.11111111111111,
                    'gsus4' => 0.11111111111111,
                    'b' => 0.11111111111111,
                    'bsus4' => 0.11111111111111,
                    'cmsus4' => 0.11111111111111,
                    'cm6' => 0.11111111111111,
                ],
                'hard' => [
                    'bm7' => 0.11111111111111,
                    'e' => 0.11111111111111,
                    'c' => 0.11111111111111,
                    'g' => 0.22222222222222,
                    'b7' => 0.11111111111111,
                    'f' => 0.11111111111111,
                    'em' => 0.11111111111111,
                    'a' => 0.11111111111111,
                    'cmaj7' => 0.11111111111111,
                    'em7' => 0.11111111111111,
                    'a7' => 0.11111111111111,
                    'f7' => 0.11111111111111,
                    'b' => 0.22222222222222,
                    'cm' => 0.11111111111111,
                    'eb' => 0.11111111111111,
                    'cdim' => 0.11111111111111,
                    'eb7' => 0.11111111111111,
                    'd7' => 0.11111111111111,
                    'db7' => 0.11111111111111,
                    'ab' => 0.11111111111111,
                    'gmaj7' => 0.11111111111111,
                    'g7' => 0.11111111111111,
                    'd#m' => 0.11111111111111,
                    'g#' => 0.11111111111111,
                    'f#' => 0.11111111111111,
                    'g#m' => 0.11111111111111,
                    'c#' => 0.11111111111111,
                ]
            ]

        ], $service->execute());
    }

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
