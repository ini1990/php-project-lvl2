<?php

namespace Differ\decoder;

use Symfony\Component\Yaml\Yaml;

function decode($file)
{
    $filePath = (strpos($file, '/') === 0) ? $file : getcwd() . '/' . $file;
    return parse(file_get_contents($filePath), pathinfo($filePath, PATHINFO_EXTENSION));
}

function parse($data, $type)
{
    $parsers = [
        'yaml' => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        'yml'  => fn($data) => Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP),
        'json' => fn($data) => json_decode($data)
    ];
    return $parsers[$type]($data);
}
