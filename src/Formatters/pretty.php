<?php

namespace Differ\Formatters\pretty;

use Funct\Collection;

function render($tree)
{
    return renderTree($tree);
}

function renderTree($tree, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $renderedData = Collection\flatten(array_map(fn ($node) => formatNode($node, $depth), $tree));
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function formatNode($node, $depth)
{
    if (!key_exists('type', $node)) {
        return getText(" ", key($node), current($node));
    }
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
        default:
            throw new \Exception("Unknown type: '{$node['type']}'!");
    }
}

function formatValue($data, $depth)
{
    if (is_array($data)) {
        return renderTree(array_chunk($data, 1, true), $depth + 1);
    }
    return trim(json_encode($data), '"');
}

function getText($sign, $key, $value)
{
    return sprintf('%3s %s: %s', $sign, $key, $value);
}
