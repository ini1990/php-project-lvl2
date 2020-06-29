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
    switch ($node['type'] ?? '') {
        case "unchanged":
            $acc[] = sprintf('%3s %s: %s', " ", $name, renderValue($oldValue, $depth + 1));
            break;
        case "added":
            $acc[] = sprintf('%3s %s: %s', "+", $name, renderValue($newValue, $depth + 1));
            break;
        case "removed":
            $acc[] = sprintf('%3s %s: %s', "-", $name, renderValue($oldValue, $depth + 1));
            break;
        case "changed":
            $acc[] = sprintf("%3s %s: %s", "+", $name, renderValue($newValue, $depth + 1));
            $acc[] = sprintf('%3s %s: %s', "-", $name, renderValue($oldValue, $depth + 1));
            break;
        case "nested":
            $acc[] = sprintf('%3s %s: %s', " ", $name, renderTree($children, $depth + 1));
            break;
        default:
            $acc[] = sprintf('%3s %s: %s', " ", key($node), current($node));
    }
    return $acc;
}

function renderValue($data, $depth)
{
    if (!is_array($data)) {
        return trim(json_encode($data), '"');
    } else {
        return renderTree(array_chunk($data, 1, true), $depth);
    }
}
