<?php

namespace Differ\diff;

function genDiff($file1, $file2, $format = 'pretty')
{
    [$arr1, $arr2] = [\Differ\decoder\decode($file1), \Differ\decoder\decode($file2)];
    $arr = array_merge(getItems($arr1, $arr2, ' '), getItems($arr2, $arr1, '-'), getItems($arr1, $arr2, '+'));
    return \Differ\renderer\rend(sortKey($arr));
}

function getItems($arr1, $arr2, $v)
{
    $arr = ($v === ' ') ? array_intersect_assoc($arr1, $arr2) : array_diff_assoc($arr1, $arr2);
    return addPrefix($arr, $v);
}

function addPrefix($arr, $p)
{
    return array_combine(array_map(fn($key) => "{$p} \"{$key}\"", array_keys($arr)), array_values($arr));
}

function sortKey($arr)
{
    uksort($arr, fn($a, $b) => trim($a, '+-= ') <=> trim($b, '+-= '));
    return $arr;
}
