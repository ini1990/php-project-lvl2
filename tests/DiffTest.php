<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\diff\genDiff;

class DiffTest extends TestCase
{
    public function testGenDiff()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expected";
        $pathToFileBefore = __DIR__ . "/fixtures/before.json";
        $pathToFileAfter = __DIR__ . "/fixtures/after.json";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }
}
