<?php

namespace Differ\Formatters\Renders;

use function Differ\Formatters\Json\renderJson;
use function Differ\Formatters\Plain\renderPlain;
use function Differ\Formatters\Stylish\renderStylish;

function render($tree, $format)
{
    switch ($format) {
        case 'json':
            return renderJson($tree);
        case 'plain':
            return renderPlain($tree);
        case 'stylish':
            return renderStylish($tree);
        default:
            throw new \Exception("Unknown output format: '{$format}'!\n");
    }
}
