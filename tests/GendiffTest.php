<?php

namespace DifferencesGenerator\Tests;

use PHPUnit\Framework\TestCase;

use function DifferencesGenerator\Gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
		$expected = file_get_contents('tests/fixtures/file.txt');
		$actual = genDiff("tests/fixtures/file1.json", "tests/fixtures/file2.json");
        $this->expectOutputString($expected, $actual);
    }
}
