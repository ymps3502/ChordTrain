<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

class MainTest extends TestCase
{
    public function testMain()
    {
        $file = str_replace(__NAMESPACE__, 'main.php', __DIR__);
        include_once($file);
        $expect = <<< string
Array
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

string;

        $this->expectOutputString($expect);
    }
}
