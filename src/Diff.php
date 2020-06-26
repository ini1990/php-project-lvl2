<?php

namespace Differ\Diff;

use function Differ\Parsers\parse;

function genDiff($file1, $file2, $format = 'pretty')
{
    $getData = fn ($file) => parse(file_get_contents(realpath($file)), pathinfo(realpath($file), PATHINFO_EXTENSION));
    $rend = "\\Differ\\Formatters\\{$format}\\render";
    return $rend(buildTree($getData($file1), $getData($file2)));
}

function buildTree($data1, $data2)
{
    $allKey = array_unique(array_keys((array) $data1 + (array) $data2));
    return array_map(fn ($key) => makeNode($key, $data1->$key ?? '', $data2->$key ?? ''), $allKey);
}

function makeNode($name, $oldValue, $newValue, $children = [])
{
    if ($newValue === '') {
        $type = 'removed';
    } elseif ($oldValue === '') {
        $type = 'added';
    } elseif ($newValue === $oldValue) {
        $type = 'unchanged';
    } elseif (is_object($oldValue) && is_object($newValue)) {
        $type = 'nested';
        $children = buildTree($oldValue, $newValue);
    } else {
        $type = 'changed';
    }
    return compact('name', 'type', 'oldValue', 'newValue', 'children');
}
