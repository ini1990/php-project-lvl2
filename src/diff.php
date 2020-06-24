<?php

namespace Differ\Diff;

use function Differ\Parsers\parse;

function genDiff($file1, $file2, $format = 'pretty')
{
    $getData = fn ($file) => parse(file_get_contents(realpath($file)), pathinfo(realpath($file), PATHINFO_EXTENSION));
    $rend = "\\Differ\\Formatters\\{$format}\\rend";
    return $rend(makeAst($getData($file1), $getData($file2)));
}

function makeAst($data1, $data2)
{
    return array_map(function ($key) use ($data1, $data2) {
        if (is_object($data1->$key ?? '') && is_object($data2->$key ?? '')) {
            [$type, $children] = ['nested', makeAst($data1->$key, $data2->$key)];
        } elseif (!property_exists($data2, $key)) {
            $type = 'removed';
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
