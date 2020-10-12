<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseFile($file)
{
    if (!file_exists($file)) {
        throw new \Exception("File '{$file}' is not exist or the specified path is incorrect\n");
    }
    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

    switch ($fileExtension) {
        case 'json':
            return $dataToArray = json_decode(file_get_contents($file));
        case 'yml':
            return $dataToArray = Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
        case 'yaml':
            return $dataToArray = Yaml::parseFile($file, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Unsupported or unknown format: '{$fileExtension}'\n");
    }
}
