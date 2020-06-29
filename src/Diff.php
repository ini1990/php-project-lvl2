<?php

namespace Differ\Diff;

use function Funct\Collection\union;
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
    $allKeys = union(array_keys($data1), array_keys($data2));
    return array_map(function ($name) use ($data1, $data2) {
        $makeNode = fn ($type, $opt) => ['name' => $name, 'type' => $type] + $opt;
        if (!key_exists($name, $data2)) {
            return $makeNode('removed', ['oldValue' => $data1[$name]]);
        } elseif (!key_exists($name, $data1)) {
            return $makeNode('added', ['newValue' => $data2[$name]]);
        } else {
            if (is_array($data1[$name]) && is_array($data2[$name])) {
                return $makeNode('nested', ['children' => buildTree($data1[$name], $data2[$name])]);
            }
            $type = ($data1[$name] === $data2[$name]) ? 'unchanged' : 'changed';
            return $makeNode($type, ['oldValue' => $data1[$name], 'newValue' => $data2[$name]]);
        }
    }, $allKeys);
}
