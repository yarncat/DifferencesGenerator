<?php

namespace Differ\Formatters\Stylish;

use function Funct\Collection\flattenAll;

function makeIndent($level)
{
    return str_repeat(' ', $level * 4);
}

function renderStylish($tree)
{
    $iter = function ($tree, $level) use (&$iter) {
        return array_map(function ($element) use ($iter, $level) {
            $indent = makeIndent($level);
            $extraIndent = makeIndent($level - 0.5);
            switch ($element['status']) {
                case 'parent':
                    $key = "{$indent}{$element['key']}: {";
                    $body = $iter($element['children'], $level + 1);
                    $closeBracket = "{$indent}}";
                    return [$key, $body, $closeBracket];
                case 'added':
                    $normalizeValue = toString($element['value'], $level);
                    return "{$extraIndent}+ {$element['key']}: {$normalizeValue}";
                case 'deleted':
                    $normalizeValue = toString($element['value'], $level);
                    return "{$extraIndent}- {$element['key']}: {$normalizeValue}";
                case 'changed':
                    $normalizeOldValue = toString($element['oldValue'], $level);
                    $normalizeNewValue = toString($element['newValue'], $level);
                    $oldValue = "{$extraIndent}- {$element['key']}: {$normalizeOldValue}";
                    $newValue = "{$extraIndent}+ {$element['key']}: {$normalizeNewValue}";
                    return [$oldValue, $newValue];
                case 'unchanged':
                    $normalizeValue = toString($element['value'], $level);
                    return "{$indent}{$element['key']}: {$normalizeValue}";
                default:
                    throw new \Exception("Tree rendering error: unknown node type");
            }
        }, $tree);
    };
    $result = flattenAll($iter($tree, 1));
    return "{" . "\n" . implode("\n", $result) . "\n" . "}";
}

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
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
