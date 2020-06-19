<?php

namespace Differ\renderer;

function rend($arr)
{
    $res = array_reduce($arr, function ($acc, $node) {
        switch ($node['type']) {
            case 'deleted':
                $acc["- " . $node['name']] = $node['oldValue'];
                break;
            case 'added':
                $acc["+ " . $node['name']] = $node['newValue'];
                break;
            case 'unchanged':
                $acc["  " . $node['name']] = $node['oldValue'];
                break;
            case 'nested':
                $acc[$node['name']] = rend($node['children']);
                break;
            case 'changed':
                $acc["- " . $node['name']] = $node['oldValue'];
                $acc["+ " . $node['name']] = $node['newValue'];
                break;
        }
        return $acc;
    }, []);
    $res = json_encode($res, JSON_PRETTY_PRINT);

    $res = str_replace(['"+ ', '"- ', '"  '], ['+ ', '- ', '  '], $res);
    $res = str_replace(['"', ','], '', $res);
    $res = str_replace(['\n'], PHP_EOL, $res);
    return $res;
}
