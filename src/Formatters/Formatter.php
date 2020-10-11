<?php

namespace DifferencesGenerator\Formatters\Formatter;

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

function toString($value, $level = 0)
{
    $indent = makeIndent($level + 1);
    $extraIndent = makeIndent($level);
    $valueToArray = (array)$value;
    $keys = array_keys($valueToArray);

    if (is_object($value)) {
        $result = array_map(function ($key) use ($valueToArray, $level, $indent) {
            $normalizeValue = toString($valueToArray[$key], $level + 1);
            return "{$indent}{$key}: {$normalizeValue}";
        }, $keys);

        return "{\n" . implode("\n", $result) . "\n{$extraIndent}}";
    } elseif (is_array($value)) {
        $result = array_map(function ($key) use ($valueToArray, $level, $indent) {
            $normalizeValue = toString($valueToArray[$key], $level + 1);
            return "{$indent}{$normalizeValue}";
        }, $keys);

        return "[\n" . implode("\n", $result) . "\n{$extraIndent}]";
    }
    return boolToString($value);
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
