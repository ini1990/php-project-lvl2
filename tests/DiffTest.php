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

    public function testGenDiffYaml()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expected";
        $pathToFileBefore = __DIR__ . "/fixtures/before.yaml";
        $pathToFileAfter = __DIR__ . "/fixtures/after.yaml";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }

    public function testGenDiff2()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expected2";
        $pathToFileBefore = __DIR__ . "/fixtures/before2.json";
        $pathToFileAfter = __DIR__ . "/fixtures/after2.json";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }

    public function testGenDiffYaml2()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expected2";
        $pathToFileBefore = __DIR__ . "/fixtures/before2.yaml";
        $pathToFileAfter = __DIR__ . "/fixtures/after2.yaml";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }

    public function testGenDiffPlain()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedPlain";
        $pathToFileBefore = __DIR__ . "/fixtures/before2.yaml";
        $pathToFileAfter = __DIR__ . "/fixtures/after2.yaml";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter, 'plain'));
    }
}
