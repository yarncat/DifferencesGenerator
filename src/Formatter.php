<?php

namespace DifferencesGenerator\Formatter;

function boolToString($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    return $value;
}

function toString($value, $level)
{
    $indent = str_repeat(' ', ($level + 1) * 4);
    $extraIndent = str_repeat(' ', $level * 4);

    if (is_object($value)) {
        $value = (array)$value;
        $keys = array_keys($value);

        $result = array_map(function ($key) use ($value, $level, $indent) {
            $normalizeValue = toString($value[$key], $level + 1);
            return "{$indent}{$key}: {$normalizeValue}";
        }, $keys);

        return "{\n" . implode("\n", $result) . "\n{$extraIndent}}";
    } elseif (is_array($value)) {
        $keys = array_keys($value);

        $result = array_map(function ($key) use ($value, $level, $indent) {
            $normalizeValue = toString($value[$key], $level + 1);
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

function render($tree, $level = 1)
{
    $tree = (array)$tree;
    $result = array_reduce($tree, function ($acc, $element) use ($level) {
        $indent = str_repeat(' ', 4 * $level);
        $extraIndent = str_repeat(' ', 4 * $level - 2);
        if ($element['status'] === 'parent') {
            $acc[] = "{$indent}{$element['key']}: {";
            $acc[] = render($element['children'], $level + 1);
            $acc[] = "{$indent}}";
        } elseif ($element['status'] === 'added') {
            $normalizeValue = toString($element['value'], $level);
            $acc[] = "{$extraIndent}+ {$element['key']}: {$normalizeValue}";
        } elseif ($element['status'] === 'deleted') {
            $normalizeValue = toString($element['value'], $level);
            $acc[] = "{$extraIndent}- {$element['key']}: {$normalizeValue}";
        } elseif ($element['status'] === 'changed') {
            $normalizeOldValue = toString($element['oldValue'], $level);
            $normalizeNewValue = toString($element['newValue'], $level);
            $acc[] = "{$extraIndent}- {$element['key']}: {$normalizeOldValue}";
            $acc[] = "{$extraIndent}+ {$element['key']}: {$normalizeNewValue}";
        } elseif ($element['status'] === 'unchanged') {
            $normalizeValue = toString($element['value'], $level);
            $acc[] = "{$indent}{$element['key']}: {$normalizeValue}";
        }
        return $acc;
    }, []);
    
    return flatten($result);
}
