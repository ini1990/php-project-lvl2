<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Diff\genDiff;

class DiffTest extends TestCase
{
    private $dir = __DIR__ . "//fixtures//";
    
    /** @return array ['input', 'expected'] */
    public function genDiffProvider()
    {
        return [
            ['before.json', 'after.json', 'pretty', 'pretty'],
            ['before.yaml', 'after.yaml', 'pretty', 'pretty'],
            ['before.json', 'after.json', 'plain', 'plain'],
            ['before.json', 'after.json', 'json', 'json']
        ];
    }

    /** @dataProvider genDiffProvider */
    public function testDiffer($before, $after, $format, $expected)
    {
        $result = file_get_contents($this->dir . $expected);
        $this->assertEquals($result, genDiff($this->dir . $before, $this->dir . $after, $format));
    }

    public function testExceptionFormat()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unknown format');
        genDiff($this->dir . 'before.html', $this->dir . 'after.html');
    }
}
