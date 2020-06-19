<?php

namespace Differ\diff;

function genDiff($file1, $file2, $format = 'pretty')
{
    [$arr1, $arr2] = [\Differ\decoder\decode($file1), \Differ\decoder\decode($file2)];
    $arr = buildAst($arr1, $arr2);
    return \Differ\renderer\rend($arr);
}

function buildAst($data1, $data2)
{
    $allKeys = array_unique(array_keys((array)$data1 + (array) $data2));
    $addDescriptionTree = array_map(function ($key) use ($data1, $data2) {
        $newValue = $data2->$key ?? '';
        $oldValue = $data1->$key ?? '';
        if (!property_exists($data1, $key)) {
            return makeNode($key, 'added', $newValue, $oldValue);
        }
        if (!property_exists($data2, $key)) {
            return makeNode($key, 'deleted', $newValue, $oldValue);
        }
        if (is_object($oldValue) && is_object($newValue)) {
            $children = buildAst($oldValue, $newValue);
            return makeNode($key, 'nested', $newValue, $oldValue, $children);
        }
        if ($oldValue === $newValue) {
            return makeNode($key, 'unchanged', $newValue, $oldValue);
        } else {
            return makeNode($key, 'changed', $newValue, $oldValue);
        }
    }, $allKeys);
    return $addDescriptionTree;
}

function makeNode($key, $type, $newValue, $oldValue = null, $children = null)
{
    return [
        'name' => $key,
        'type' => $type,
        'oldValue' => $oldValue,
        'newValue' => $newValue,
        'children' => $children
    ];
}
