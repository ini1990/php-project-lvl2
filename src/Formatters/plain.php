<?php

namespace Differ\Formatters\plain;

function render($tree)
{
    return iter($tree);
}

function iter($tree, $parrent = '')
{
    $result = array_reduce($tree, fn ($acc, $node) => array_merge($acc, [generateText($node, $parrent)]), []);
    return implode("\n", array_filter($result));
}

function generateText($node, $parrent)
{
    $name = ltrim("{$parrent}.{$node['name']}", '.');
    $format = fn ($value) => is_array($value) ? 'complex value' : trim(json_encode($value), '"');
    switch ($node['type']) {
        case 'removed':
            return sprintf("Property '%s' was %s", $name, $node['type']);
            break;
        case 'added':
            return sprintf("Property '%s' was %s with value: '%s'", $name, $node['type'], $format($node['newValue']));
            break;
        case 'changed':
            [$newValue, $oldValue] = [$format($node['newValue']), $format($node['oldValue'])];
            return sprintf("Property '%s' was %s. From '%s' to '%s'", $name, $node['type'], $oldValue, $newValue);
            break;
        case 'unchanged':
            break;
        case 'nested':
            return iter($node['children'], $name);
            break;
        default:
            throw new \Exception("Unknown type: '{$node['type']}'!");
            break;
    }
}
