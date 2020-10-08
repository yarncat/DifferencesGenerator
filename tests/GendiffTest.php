<?php

namespace DifferencesGenerator\Tests;

use PHPUnit\Framework\TestCase;

use function DifferencesGenerator\Gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiffForFlatJson()
    {
        $expected = file_get_contents('tests/fixtures/flatFilesDiff.txt');
        $actualJson = genDiff('tests/fixtures/fileFlat1.json', 'tests/fixtures/fileFlat2.json');
        $this->expectOutputString($expected, $actualJson);
    }

    public function testGendiffForFlatYaml()
    {
        $expected = file_get_contents('tests/fixtures/flatFilesDiff.txt');
        $actualYaml = genDiff('tests/fixtures/fileFlat1.yml', 'tests/fixtures/fileFlat2.yml');
        $this->expectOutputString($expected, $actualYaml);
    }

    public function testGendiffForNestedJson()
    {
        $expected = file_get_contents('tests/fixtures/nestedFilesDiff.txt');
        $actualJson = genDiff('tests/fixtures/fileNested1.json', 'tests/fixtures/fileNested2.json');
        $this->expectOutputString($expected, $actualJson);
    }

    public function testGendiffForNestedYaml()
    {
        $expected = file_get_contents('tests/fixtures/nestedFilesDiff.txt');
        $actualYaml = genDiff('tests/fixtures/fileNested1.yml', 'tests/fixtures/fileNested2.yml');
        $this->expectOutputString($expected, $actualYaml);
    }
}
