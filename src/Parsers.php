<?php

namespace DifferencesGenerator\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile($file)
{
    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

    if ($fileExtension === 'json') {
        return $dataToArray = json_decode(file_get_contents($file));
    } elseif ($fileExtension === 'yml' || $fileExtension === 'yaml') {
        return $dataToArray = Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
    }
}
