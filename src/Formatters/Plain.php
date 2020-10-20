<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;

function normalize($value)
{
    if (is_object($value) || is_array($value)) {
        return '[complex value]';
    } elseif (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return "'{$value}'";
}

function renderPlain($tree)
{
    $iter = function ($tree, $root) use (&$iter) {
        return array_map(function ($element) use ($iter, $root) {
            $path = "{$root}{$element['key']}";
            switch ($element['status']) {
                case 'parent':
                    return $iter($element['children'], "{$path}.");
                case 'added':
                    $normalizeValue = normalize($element['value']);
                    return "Property '{$path}' was added with value: {$normalizeValue}";
                case 'deleted':
                    return "Property '{$path}' was removed";
                case 'changed':
                    $normalizeOldValue = normalize($element['oldValue']);
                    $normalizeNewValue = normalize($element['newValue']);
                    return "Property '{$path}' was updated. From {$normalizeOldValue} to {$normalizeNewValue}";
                case 'unchanged':
                    return [];
                default:
                    throw new \Exception("Tree rendering error: unknown node type");
            }
        }, $tree);
    };
    $result = flattenAll($iter($tree, ''));
    return implode("\n", $result);
}
