<?php

namespace Differ\diff;

use function Differ\decoder\decode;

function genDiff($file1, $file2, $format = 'pretty')
{
    $rend = "\Differ\\formatters\\{$format}\\rend";
    return $rend(buildAst(decode($file1), decode($file2)));
}

function buildAst($data1, $data2)
{
    return array_map(function ($key) use ($data1, $data2) {
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
            'children' => $children ?? []];
    }, array_unique(array_keys((array) $data1 + (array) $data2)));
}
