<?php

namespace DifferencesGenerator\Parsers;

use Symfony\Component\Yaml\Yaml;

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function parseFile($file)
{
    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

    if ($fileExtension === 'json') {
        $fileToArray = json_decode(file_get_contents($file), true);
    } elseif ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        $fileToArray = Yaml::parseFile($file);
    }

    return array_map(function ($element) {
        return boolToString($element);
    }, $fileToArray);
}
