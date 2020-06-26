<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $type)
{
    $parsers = [
        'yaml' => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        'yml'  => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        'json' => fn($data) => json_decode($data)
    ];
    if (!array_key_exists(strtolower($type), $parsers)) {
        throw new \Exception("Undefined format: {$type}");
    }
    return $parsers[strtolower($type)]($data);
}
