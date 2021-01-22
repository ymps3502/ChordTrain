<?php

namespace Tests;

use ChordTrain\ClassifyService;
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

        $service->execute();
    }
}