<?php

namespace DifferencesGenerator\Gendiff;

function boolToString($value)
{
    if (is_bool($value)) {
		return $value ? 'true' : 'false';
	}
	return $value;
}

function jsonDecode($file)
{
	$fileToArray = json_decode(file_get_contents($file), true);
	return array_map(function($element) {
		return boolToString($element);
	}, $fileToArray);
}

function genDiff($firstFile, $secondFile)
{
	$file1 = jsonDecode($firstFile);
	$file2 = jsonDecode($secondFile);
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
