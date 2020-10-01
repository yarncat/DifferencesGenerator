<?php

namespace DifferencesGenerator\Tests;

use PHPUnit\Framework\TestCase;

use function DifferencesGenerator\Gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiffJson()
    {
		$expected = file_get_contents('tests/fixtures/file.txt');
		$actualJson = genDiff("tests/fixtures/file1.json", "tests/fixtures/file2.json");
        $this->expectOutputString($expected, $actualJson);
    }
	
	public function testGendiffYaml()
    {
		$expected = file_get_contents('tests/fixtures/file.txt');
		$actualYaml = genDiff("tests/fixtures/file1.yml", "tests/fixtures/file2.yml");
		$this->expectOutputString($expected, $actualYaml);
    }
}
