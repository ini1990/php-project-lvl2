<?php

namespace Differ\Formatters\pretty;

use function Funct\Collection\flatten;

function render($tree)
{
    return renderTree($tree);
}

function renderTree($tree, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $renderedData = flatten(array_map(fn ($node) => formatNode($node, $depth), $tree));
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function formatNode($node, $depth)
{
    switch ($node['type']) {
        case "unchanged":
            return getText(' ', $node['name'], formatValue($node['oldValue'], $depth));
        case "added":
            return getText('+', $node['name'], formatValue($node['newValue'], $depth));
        case "removed":
            return getText('-', $node['name'], formatValue($node['oldValue'], $depth));
        case "changed":
            return  [getText('+', $node['name'], formatValue($node['newValue'], $depth)),
                    getText('-', $node['name'], formatValue($node['oldValue'], $depth))];
        case "nested":
            return getText(' ', $node['name'], renderTree($node['children'], $depth + 1));
        case 'itemsToFormat':
            return getText(" ", key($node), current($node));
            break;
        default:
            throw new \Exception("Unknown type: '{$node['type']}'!");
    }
}

function formatValue($data, $depth)
{
    if (is_array($data)) {
        $items = array_merge($data, ['type' => 'itemsToFormat']);
        return renderTree(array_chunk($items, 2, true), $depth + 1);
    }
    return trim(json_encode($data), '"');
}

function getText($sign, $key, $value)
{
    return sprintf('%3s %s: %s', $sign, $key, $value);
}
