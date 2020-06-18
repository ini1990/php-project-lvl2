<?php

namespace Differ\decoder;

function decode($file)
{
    return json_decode(getContent(getFilePath($file)), true);
}

function getFilePath(string $file): string
{
    return strpos($file, '/') === 0 ? $file : getcwd() . '/' . $file;
}

function getContent($path)
{
    if (!is_readable($path)) {
        throw new \Exception("'{$path}' is not readble");
    }
    return file_get_contents($path);
}
