<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Diff\genDiff;

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

    public function testGenDiffTree()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedTree";
        $pathToFileBefore = __DIR__ . "/fixtures/beforeTree.json";
        $pathToFileAfter = __DIR__ . "/fixtures/afterTree.json";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }

    public function testGenDiffYamlTree()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedTree";
        $pathToFileBefore = __DIR__ . "/fixtures/beforeTree.yaml";
        $pathToFileAfter = __DIR__ . "/fixtures/afterTree.yaml";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter));
    }

    public function testGenDiffYamlPlainTree()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedPlain";
        $pathToFileBefore = __DIR__ . "/fixtures/beforeTree.yaml";
        $pathToFileAfter = __DIR__ . "/fixtures/afterTree.yaml";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter, 'plain'));
    }

    public function testGenDiffJsonFormat()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedJson";
        $pathToFileBefore = __DIR__ . "/fixtures/before.json";
        $pathToFileAfter = __DIR__ . "/fixtures/after.json";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter, 'json'));
    }

    public function testGenDiffJsonTreeFormat()
    {
        $pathToFileExpected = __DIR__ . "/fixtures/expectedJsonTree";
        $pathToFileBefore = __DIR__ . "/fixtures/beforeTree.json";
        $pathToFileAfter = __DIR__ . "/fixtures/afterTree.json";
        $expected = file_get_contents($pathToFileExpected);
        $this->assertEquals($expected, genDiff($pathToFileBefore, $pathToFileAfter, 'json'));
    }
}
