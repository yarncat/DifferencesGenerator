<?php

namespace DifferencesGenerator\Formatters\Stylish;

use function DifferencesGenerator\Formatters\Formatter\flatten;
use function DifferencesGenerator\Formatters\Formatter\makeIndent;
use function DifferencesGenerator\Formatters\Formatter\toString;

function renderStylish($tree, $level = 1)
{
    $result = array_reduce($tree, function ($acc, $element) use ($level) {
        $indent = makeIndent($level);
        $extraIndent = makeIndent($level - 0.5);
        if ($element['status'] === 'parent') {
            $acc[] = "{$indent}{$element['key']}: {";
            $acc[] = renderStylish($element['children'], $level + 1);
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
