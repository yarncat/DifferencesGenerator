<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Gendiff\genDiff;

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
            ['flatFile1.json', 'flatFile2.json', 'stylish', 'flatFilesDiff.txt'],
            ['nestedStructure1.json', 'nestedStructure2.json', 'stylish', 'nestedStructuresDiff.txt'],
            ['nestedStructure1.json', 'nestedStructure2.json', 'plain', 'plainDiff.txt'],
            ['nestedStructure1.json', 'nestedStructure2.json', 'json', 'jsonDiff.txt'],
            ['composer1.json', 'composer2.json', 'stylish', 'composerDiff.txt'],
            ['flatFile1.yaml', 'flatFile2.yaml', 'stylish', 'flatFilesDiff.txt'],
            ['nestedStructure1.yml', 'nestedStructure2.yml', 'stylish', 'nestedStructuresDiff.txt']
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
            ['flatFile1.json', 'flatFile2.json', 'lolkek', "Unknown output format: 'lolkek'!\n"]
        ];
    }
}
