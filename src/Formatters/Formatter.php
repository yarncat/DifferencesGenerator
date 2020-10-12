<?php

namespace Differ\Formatters\Formatter;

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function makeIndent($level)
{
    return str_repeat(' ', $level * 4);
}

function flatten($array)
{
    return array_reduce($array, function ($acc, $element) {
        if (is_array($element)) {
            return array_merge($acc, flatten($element));
        }
        $acc[] = $element;
        return $acc;
    }, []);
}
