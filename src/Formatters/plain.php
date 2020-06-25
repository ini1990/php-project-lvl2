<?php

namespace Differ\Formatters\plain;

function rend($ast, $parrent = '')
{
    $result = array_reduce($ast, function ($acc, $node) use ($parrent) {
        $rendValue = fn ($str) => is_object($node[$str]) ? 'complex value' : trim(json_encode($node[$str]), '"');
        $rendText = fn ($data) => "Property '{$parrent}{$node['name']}' was {$node['type']}{$data}";
        $arr = ['added' => $rendText(" with value: '{$rendValue('newValue')}'"),
            'removed' => $rendText(''),
            'changed' => $rendText(". From '{$rendValue('oldValue')}' to '{$rendValue('newValue')}'")];
        return array_merge($acc, [($arr[$node['type']] ?? rend($node['children'], "{$parrent}{$node['name']}."))]);
    }, []);
    
    return implode("\n", array_filter($result));
}
