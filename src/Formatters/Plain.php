<?php

namespace Differ\Formatters\Plain;

use function Differ\Formatters\Formatter\boolToString;
use function Differ\Formatters\Formatter\flatten;

function normalize($value)
{
    if (is_object($value) || is_array($value)) {
        return '[complex value]';
    } elseif (is_bool($value)) {
        return boolToString($value);
    }
    return "'{$value}'";
}

function renderPlain($tree)
{
    $iter = function ($tree, $root) use (&$iter) {
        return array_reduce($tree, function ($acc, $element) use ($iter, $root) {
            $path = "{$root}{$element['key']}";
            if ($element['status'] === 'parent') {
                $acc[] = $iter($element['children'], "{$path}.");
            } elseif ($element['status'] === 'added') {
                $normalizeValue = normalize($element['value']);
                $acc[] = "Property '{$path}' was added with value: {$normalizeValue}";
            } elseif ($element['status'] === 'deleted') {
                $acc[] = "Property '{$path}' was removed";
            } elseif ($element['status'] === 'changed') {
                $normalizeOldValue = normalize($element['oldValue']);
                $normalizeNewValue = normalize($element['newValue']);
                $acc[] = "Property '{$path}' was updated. From {$normalizeOldValue} to {$normalizeNewValue}";
            }
            return $acc;
        }, []);
    };
    $result = flatten($iter($tree, ''));
    return implode("\n", $result) . "\n";
}
