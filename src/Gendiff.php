<?php

namespace DifferencesGenerator\Gendiff;

use function DifferencesGenerator\Parsers\parseFile;
use function DifferencesGenerator\Formatters\Plain\renderPlain;
use function DifferencesGenerator\Formatters\Stylish\renderStylish;

function genDiff($firstFile, $secondFile, $format = 'stylish')
{
    $file1 = parseFile($firstFile);
    $file2 = parseFile($secondFile);

    $tree = buildTree($file1, $file2);

    if ($format === 'plain') {
        $result = renderPlain($tree);
        return implode("\n", $result) . "\n";
    }
    $result = renderStylish($tree);
    return "{" . "\n" . implode("\n", $result) . "\n" . "}" . "\n";
}

function buildTree($data1, $data2)
{
    $data1 = (array)$data1;
    $data2 = (array)$data2;
    $keys = array_keys(array_merge($data1, $data2));
    sort($keys);

    return array_map(function ($key) use ($data1, $data2) {
        if (!array_key_exists($key, $data2)) {
            return ['key' => $key, 'status' => 'deleted', 'value' => $data1[$key]];
        }
        if (!array_key_exists($key, $data1)) {
            return ['key' => $key, 'status' => 'added', 'value' => $data2[$key]];
        }
        if (is_object($data1[$key]) && is_object($data2[$key])) {
            return ['key' => $key, 'status' => 'parent', 'children' => buildTree($data1[$key], $data2[$key])];
        } elseif (
            ((is_array($data1[$key]) && is_array($data2[$key])) && ($data1[$key] == $data2[$key]))
            || ($data1[$key] === $data2[$key])
        ) {
            return ['key' => $key, 'status' => 'unchanged', 'value' => $data1[$key]];
        } else {
            return ['key' => $key, 'status' => 'changed', 'oldValue' => $data1[$key], 'newValue' => $data2[$key]];
        }
    }, $keys);
}
