<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $dataType)
{
    switch ($dataType) {
        case 'json':
            return $dataToArray = json_decode($data);
        case 'yml':
        case 'yaml':
            return $dataToArray = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Unsupported or unknown format: '{$dataType}'\n");
    }
}
