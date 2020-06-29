<?php

namespace Differ\Formatters\plain;

function render($tree)
{
    return iter($tree);
}

function iter($tree, $parent = '')
{
    $result = array_map(fn ($node) => generateText($node, $parent), $tree);
    return implode("\n", array_filter($result));
}

function generateText($node, $parent)
{
    $name = $parent . $node['name'];
    $format = fn ($value) => is_array($value) ? 'complex value' : trim(json_encode($value), '"');
    switch ($node['type']) {
        case 'removed':
            return sprintf("Property '%s' was %s", $name, $node['type']);
        case 'added':
            return sprintf("Property '%s' was %s with value: '%s'", $name, $node['type'], $format($node['newValue']));
        case 'changed':
            [$newValue, $oldValue] = [$format($node['newValue']), $format($node['oldValue'])];
            return sprintf("Property '%s' was %s. From '%s' to '%s'", $name, $node['type'], $oldValue, $newValue);
        case 'unchanged':
            break;
        case 'nested':
            return iter($node['children'], $name . '.');
        default:
            throw new \Exception("Unknown type: '{$node['type']}'!");
    }
}
