<?php

namespace Differ\renderer;

function rend($arr)
{
    $acc = [];
    foreach ($arr as $key => $value) {
        $acc[] = "  {$key}: {$value}";
    }
    $result = implode(",\n", $acc);
    print '{' . PHP_EOL . "{$result}" . PHP_EOL . "}" . PHP_EOL;
}
