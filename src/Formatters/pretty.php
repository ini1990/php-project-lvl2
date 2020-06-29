<?php

namespace Differ\Formatters\pretty;

function render($tree)
{
    return renderTree($tree);
}

function renderTree($tree, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $renderedData = array_reduce($tree, fn ($acc, $node) => array_merge($acc, renderNode($node, $depth)), []);
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function renderNode($node, $depth)
{
    extract($node);
    switch ($type) {
        case "unchanged":
            return getText(' ', $name, renderValue($oldValue, $depth));
        case "added":
            return getText('+', $name, renderValue($newValue, $depth));
        case "removed":
            return getText('-', $name, renderValue($oldValue, $depth));
        case "changed":
            $arr = getText('+', $name, renderValue($newValue, $depth));
            return array_merge($arr, getText('-', $name, renderValue($oldValue, $depth)));
        case "nested":
            return getText(' ', $name, renderTree($children, $depth + 1));
        default:
            return getText(" ", key($node), current($node));
    }
}

function renderValue($data, $depth)
{
    return is_array($data) ? renderTree(array_chunk($data, 1, true), $depth + 1) : trim(json_encode($data), '"');
}

function getText($sign, $key, $value)
{
    return [sprintf('%3s %s: %s', $sign, $key, $value)];
}
