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

    $ast = array_map(function ($key) use ($data1, $data2) {
        if (is_object($data1->$key ?? '') && is_object($data2->$key ?? '')) {
            [$type, $children] = ['nested', buildAst($data1->$key, $data2->$key)];
        } elseif (!property_exists($data2, $key)) {
            $type = 'deleted';
        } elseif (!property_exists($data1, $key)) {
            $type = 'added';
        } elseif ($data2->$key === $data1->$key) {
            $type = 'unchanged';
        } else {
            $type = 'changed';
        }

        return ['name' => $key,
            'type' => $type,
            'oldValue' => $data1->$key ?? '',
            'newValue' => $data2->$key ?? '',
            'children' => $children ?? ''];
    }, $allKeys);

    return $ast;
}
