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
            switch ($element['status']) {
                case 'parent':
                    $acc[] = $iter($element['children'], "{$path}.");
                    break;
                case 'added':
                    $normalizeValue = normalize($element['value']);
                    $acc[] = "Property '{$path}' was added with value: {$normalizeValue}";
                    break;
                case 'deleted':
                    $acc[] = "Property '{$path}' was removed";
                    break;
                case 'changed':
                    $normalizeOldValue = normalize($element['oldValue']);
                    $normalizeNewValue = normalize($element['newValue']);
                    $acc[] = "Property '{$path}' was updated. From {$normalizeOldValue} to {$normalizeNewValue}";
                    break;
                case 'unchanged':
                    $acc[] = [];
                    break;
                default:
                    throw new \Exception("Tree rendering error: unknown node type\n");
            }
            return $acc;
        }, []);
    };
    $result = flatten($iter($tree, ''));
    return implode("\n", $result) . "\n";
}
