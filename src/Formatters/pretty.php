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
    $renderedData = flatten(array_map(function ($node) use ($depth) {
        switch ($node['type']) {
            case "unchanged":
                return stringify($node['name'], $node['oldValue'], $depth);
            case "added":
                return stringify($node['name'], $node['newValue'], $depth, '+');
            case "removed":
                return stringify($node['name'], $node['oldValue'], $depth, '-');
            case "changed":
                return  [stringify($node['name'], $node['newValue'], $depth, '+'),
                stringify($node['name'], $node['oldValue'], $depth, '-')];
            case "nested":
                return stringify($node['name'], renderTree($node['children'], $depth + 1), $depth);
            default:
                throw new \Exception("Unknown type: '{$node['type']}'!");
        }
    }, $tree));
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function stringify($key, $data, $depth = 0, $sign = ' ')
{
    if (is_bool($data)) {
        $data = ($data) ? 'true' : 'false';
    } elseif (is_array($data)) {
        $indent = str_repeat('    ', $depth + 1);
        $renderedData = array_map(fn($item) => stringify(array_search($item, $data), $item), $data);
        $data = "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
    }
    return sprintf('%3s %s: %s', $sign, $key, $data);
}
