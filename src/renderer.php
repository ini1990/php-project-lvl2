<?php

namespace Differ\renderer;

function rend($arr)
{
    $acc = [];
    foreach ($arr as $key => $value) {
        $value = trim(var_export($value, true), "'");
        $acc[] = "  {$key}: {$value}";
    }
    $result = implode("\n", $acc);
    return '{' . PHP_EOL . "{$result}" . PHP_EOL . "}";
}
