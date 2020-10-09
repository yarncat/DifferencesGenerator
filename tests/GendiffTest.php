<?php

namespace DifferencesGenerator\Tests;

use PHPUnit\Framework\TestCase;

use function DifferencesGenerator\Gendiff\genDiff;

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
            ['composer1.json', 'composer2.json', 'stylish', 'composerDiff.txt'],
            ['flatFile1.yml', 'flatFile2.yml', 'stylish', 'flatFilesDiff.txt'],
            ['nestedStructure1.yml', 'nestedStructure2.yml', 'stylish', 'nestedStructuresDiff.txt']
        ];
    }
}
