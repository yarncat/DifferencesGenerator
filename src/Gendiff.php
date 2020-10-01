<?php

namespace DifferencesGenerator\Gendiff;

use function DifferencesGenerator\Parsers\parseFile;

function genDiff($firstFile, $secondFile)
{
    $file1 = parseFile($firstFile);
    $file2 = parseFile($secondFile);
    $keys = array_keys(array_merge($file1, $file2));
    sort($keys);

    $result = array_reduce($keys, function ($acc, $key) use ($file1, $file2) {
        if (array_key_exists($key, $file1) && array_key_exists($key, $file2)) {
            if ($file1[$key] === $file2[$key]) {
                $acc[] = "    {$key}: {$file1[$key]}";
            }
            if ($file1[$key] !== $file2[$key]) {
                $acc[] = "  - {$key}: {$file1[$key]}";
                $acc[] = "  + {$key}: {$file2[$key]}";
            }
        }
        if (!array_key_exists($key, $file1)) {
            $acc[] = "  + {$key}: {$file2[$key]}";
        }
        if (!array_key_exists($key, $file2)) {
            $acc[] = "  - {$key}: {$file1[$key]	}";
        }
        return $acc;
    }, []);
    $finResult = implode("\n", $result);
    echo "{\n$finResult\n}\n";
}
