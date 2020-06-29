<?php

namespace Differ\Formatters\pretty;

function render($tree)
{
    return renderTree($tree);
}

function renderTree($tree, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $renderedData = array_reduce($tree, fn ($acc, $node) => array_merge($acc, formatNode($node, $depth)), []);
    return "{\n{$indent}" . implode("\n{$indent}", $renderedData) . "\n{$indent}}";
}

function formatNode($node, $depth)
{
    extract($node);
    switch ($type) {
        case "unchanged":
            return getText(' ', $name, formatValue($oldValue, $depth));
        case "added":
            return getText('+', $name, formatValue($newValue, $depth));
        case "removed":
            return getText('-', $name, formatValue($oldValue, $depth));
        case "changed":
            $arr = getText('+', $name, formatValue($newValue, $depth));
            return array_merge($arr, getText('-', $name, formatValue($oldValue, $depth)));
        case "nested":
            return getText(' ', $name, renderTree($children, $depth + 1));
        case 'array':
            return getText(" ", key($node), current($node));
            break;
        default:
            throw new \Exception("Unknown type: '{$node['type']}'!");
    }
}

function formatValue($data, $depth)
{
    if (is_array($data)) {
        $arr = $data + ['type' => 'array'];
        return renderTree(array_chunk($arr, 2, true), $depth + 1);
    } else {
        return trim(json_encode($data), '"');
    }
}

function getText($sign, $key, $value)
{
    return [sprintf('%3s %s: %s', $sign, $key, $value)];
}
