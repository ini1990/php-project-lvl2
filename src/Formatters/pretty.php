<?php

namespace Differ\Formatters\pretty;

function render($ast, $depth = 0)
{
    $indent = str_repeat('    ', $depth);
    $renderedData = array_reduce($ast, function ($acc, $node) use ($indent, $depth) {
        $oldValue = renderValue($node['oldValue'], $depth);
        $newValue = renderValue($node['newValue'], $depth);
        $children = ($node['type'] == 'nested') ? render($node['children'], $depth + 1) : '';
        $map = ['nested' => "{$indent}    {$node['name']}: {$children}",
             'unchanged' => "{$indent}    {$node['name']}: {$newValue}",
             'changed' => "{$indent}  + {$node['name']}: {$newValue}\n{$indent}  - {$node['name']}: {$oldValue}",
             'added' => "{$indent}  + {$node['name']}: {$newValue}",
             'removed' => "{$indent}  - {$node['name']}: {$oldValue}"];
        return array_merge($acc, [$map[$node['type']]]);
    }, ["{"]);
    return implode("\n", $renderedData) . "\n$indent}";
}

function renderValue($item, $depth)
{
    if (!is_object($item)) {
        return trim(json_encode($item), '"');
    }
    $indent = "\n" . str_repeat('    ', $depth + 1);
    $arr = array_keys(get_object_vars($item));
    $renderedData = array_reduce($arr, function ($acc, $key) use ($item, $indent, $depth) {
        if (is_object($item->$key)) {
            return array_merge($acc, [renderValue($item->$key, $depth + 1)]);
        } else {
            return array_merge($acc, ["$indent    $key: " . trim(json_encode($item->$key), '"')]);
        }
    }, []);
    return "{" . implode("\n", $renderedData) . "$indent}";
}
