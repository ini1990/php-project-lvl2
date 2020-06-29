<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $type)
{
    $parsers = [
        'yaml' => fn($data) => Yaml::parse($data),
        'yml'  => fn($data) => Yaml::parse($data),
        'json' => fn($data) => json_decode($data, true)
    ];
    if (!array_key_exists(strtolower($type), $parsers)) {
        throw new \Exception("Unknown format: {$type}");
    }
    return $parsers[strtolower($type)]($data);
}
