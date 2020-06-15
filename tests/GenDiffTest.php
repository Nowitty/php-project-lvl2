<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $fixtures;

    protected function setUp(): void
    {
        $this->fixtures = __DIR__ . '/fixtures/';
    }

    public function testGenDiff()
    {
        $filePath1 = $this->fixtures . 'firstJson.json';
        $filePath2 = $this->fixtures . 'secondJson.json';
        $expected = file_get_contents($this->fixtures . 'result');
        $this->assertEquals($expected, genDiff($filePath1, $filePath2));

        $filePath1 = $this->fixtures . 'firstYml.yml';
        $filePath2 = $this->fixtures . 'secondYml.yml';
        $expected = file_get_contents($this->fixtures . 'result');
        $this->assertEquals($expected, genDiff($filePath1, $filePath2));

        $filePath1 = $this->fixtures . 'firstJsonTree.json';
        $filePath2 = $this->fixtures . 'secondJsonTree.json';
        $expected = file_get_contents($this->fixtures . 'resultTree');
        $this->assertEquals($expected, genDiff($filePath1, $filePath2));

        $filePath1 = $this->fixtures . 'firstJsonTree.json';
        $filePath2 = $this->fixtures . 'secondJsonTree.json';
        $expected = file_get_contents($this->fixtures . 'resultPlain');
        $this->assertEquals($expected, genDiff($filePath1, $filePath2, 'plain'));
    }
}
