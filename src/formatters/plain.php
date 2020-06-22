<?php

namespace Differ\formatters\plain;

function rend($ast, $parrent = '')
{
    $result = array_reduce($ast, function ($acc, $node) use ($parrent) {
        $oldValue = is_object($node['oldValue']) ? 'complex value' : trim(json_encode($node['oldValue']), '"');
        $newValue = is_object($node['newValue']) ? 'complex value' : trim(json_encode($node['newValue']), '"');
        $arr = ['added' => "Property '{$parrent}{$node['name']}' was added with value: '{$newValue}'",
            'deleted' => "Property '{$parrent}{$node['name']}' was removed",
            'changed' => "Property '{$parrent}{$node['name']}' was changed. From '{$oldValue}' to '{$newValue}'"];

        return array_merge($acc, [($arr[$node['type']] ?? rend($node['children'], "{$parrent}{$node['name']}."))]);
    }, []);
    return implode("\n", array_filter($result));
}
