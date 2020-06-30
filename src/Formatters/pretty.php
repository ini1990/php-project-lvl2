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
    $renderedData = Collection\flatten(array_map(function ($node) use ($depth) {
        switch ($node['type']) {
            case "unchanged":
                return stringify(' ', $node['name'], formatValue($node['oldValue'], $depth));
            case "added":
                return stringify('+', $node['name'], formatValue($node['newValue'], $depth));
            case "removed":
                return stringify('-', $node['name'], formatValue($node['oldValue'], $depth));
            case "changed":
                return  [stringify('+', $node['name'], formatValue($node['newValue'], $depth)),
                stringify('-', $node['name'], formatValue($node['oldValue'], $depth))];
            case "nested":
                return stringify(' ', $node['name'], renderTree($node['children'], $depth + 1));
            default:
                throw new \Exception("Unknown type: '{$node['type']}'!");
        }
    }, $tree));
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function formatValue($data, $depth)
{
    if (is_array($data)) {
        $indent = str_repeat('    ', ++$depth);
        $renderedData = array_map(fn($node) => stringify(' ', key($node), current($node)), array_chunk($data, 1, true));
        return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
    }
    return trim(json_encode($data), '"');
}

function stringify($sign, $key, $value)
{
    return sprintf('%3s %s: %s', $sign, $key, $value);
}
