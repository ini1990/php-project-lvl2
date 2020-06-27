<?php

namespace Differ\Diff;

use function Differ\Parsers\parse;

function genDiff($filePath1, $filePath2, $format = 'pretty')
{
    $render = "\\Differ\\Formatters\\{$format}\\render";
    $getExtension = fn ($filePath) => pathinfo(realpath($filePath), PATHINFO_EXTENSION);
    $getData = fn ($filePath) => parse(file_get_contents(realpath($filePath)), $getExtension($filePath));

    return $render(buildTree($getData($filePath1), $getData($filePath2)));
}

function buildTree($data1, $data2)
{
    $allKey = array_unique(array_keys((array) $data1 + (array) $data2));

    return array_map(function ($key) use ($data1, $data2) {
        if (!property_exists($data2, $key)) {
            return makeNode($key, 'removed', $data1->$key);
        } elseif (!property_exists($data1, $key)) {
            return makeNode($key, 'added', '', $data2->$key);
        } else {
            [$oldValue, $newValue] = [$data1->$key, $data2->$key];
            if (is_object($oldValue) && is_object($newValue)) {
                $children = buildTree($oldValue, $newValue);
                return makeNode($key, 'nested', $oldValue, $newValue, $children);
            } else {
                $type = ($oldValue === $newValue) ? 'unchanged' : 'changed';
                return makeNode($key, $type, $oldValue, $newValue);
            }
        }
    }, $allKey);
}

function makeNode($name, $type, $oldValue = '', $newValue = '', $children = [])
{
    return compact('name', 'type', 'oldValue', 'newValue', 'children');
}
