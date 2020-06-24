<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Diff\genDiff;

class DiffTest extends TestCase
{
    private $after = __DIR__ . "/fixtures/after.json";
    private $before = __DIR__ . "/fixtures/before.json";
    private $yamlBefore = __DIR__ . "/fixtures/before.yaml";
    private $yamlAfter = __DIR__ . "/fixtures/after.yaml";
    private $expectedPretty = __DIR__ . "/fixtures/pretty";
    private $expectedJson = __DIR__ . "/fixtures/json";
    private $expectedPlain = __DIR__ . "/fixtures/plain";

    public function testGenDiff()
    {
        $expected = file_get_contents($this->expectedPretty);
        $this->assertEquals($expected, genDiff($this->before, $this->after));
    }

    public function testGenDiffYaml()
    {
        $expected = file_get_contents($this->expectedPretty);
        $this->assertEquals($expected, genDiff($this->yamlBefore, $this->yamlAfter));
    }

    public function testGenDiffPlain()
    {
        $expected = file_get_contents($this->expectedPlain);
        $this->assertEquals($expected, genDiff($this->before, $this->after, 'plain'));
    }

    public function testGenDiffJson()
    {
        $expected = file_get_contents($this->expectedJson);
        $this->assertEquals($expected, genDiff($this->before, $this->after, 'json'));
    }
}
