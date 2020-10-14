<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Gendiff\genDiff;
use function Differ\Formatters\Stylish\renderStylish;
use function Differ\Formatters\Plain\renderPlain;

class GendiffTest extends TestCase
{
    public const DIR = 'tests/fixtures/';

    /**
     * @dataProvider fileProvider
     */

    public function testGendiff($firstFile, $secondFile, $format, $expectedResult)
    {
        $expected = file_get_contents(self::DIR . $expectedResult);
        $actual = genDiff(self::DIR . $firstFile, self::DIR . $secondFile, $format);
        $this->assertEquals($expected, $actual);
    }

    public function fileProvider()
    {
        return [
            ['nestedStructure1.json', 'nestedStructure2.json', 'stylish', 'stylishDiff.txt'],
            ['nestedStructure1.json', 'nestedStructure2.json', 'plain', 'plainDiff.txt'],
            ['nestedStructure1.json', 'nestedStructure2.json', 'json', 'jsonDiff.txt'],
            ['composer1.json', 'composer2.json', 'stylish', 'composerDiff.txt'],
            ['nestedStructure1.yml', 'nestedStructure2.yml', 'stylish', 'stylishDiff.txt'],
            ['nestedStructure1.yaml', 'nestedStructure2.yaml', 'stylish', 'stylishDiff.txt']
        ];
    }

    /**
     * @dataProvider uncorrectFileProvider
     */

    public function testException($firstFile, $secondFile, $format, $expected)
    {
        $this->expectExceptionMessage($expected);
        genDiff(self::DIR . $firstFile, self::DIR . $secondFile, $format);
    }

    public function uncorrectFileProvider()
    {
        return [
            ['flatFile.json', 'flatFile2.json', 'stylish',
                "File 'tests/fixtures/flatFile.json' is not exist or the specified path is incorrect\n"],
            ['jsonDiff.txt', 'plainDiff.txt', 'plain', "Unsupported or unknown format: 'txt'\n"],
            ['nestedStructure1.yaml', 'nestedStructure2.yaml', 'lolkek', "Unknown output format: 'lolkek'!\n"]
        ];
    }

    public function testInvalidTreeRenderingStylish()
    {
        $data = file_get_contents(self::DIR . 'invalidTree.txt');
        $tree = json_decode($data, true);
        $this->expectExceptionMessage("Tree rendering error: unknown node type\n");
        renderStylish($tree);
    }

    public function testInvalidTreeRenderingPlain()
    {
        $data = file_get_contents(self::DIR . 'invalidTree.txt');
        $tree = json_decode($data, true);
        $this->expectExceptionMessage("Tree rendering error: unknown node type\n");
        renderPlain($tree);
    }
}
