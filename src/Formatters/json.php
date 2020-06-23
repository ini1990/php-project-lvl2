<?php

namespace Differ\Formatters\json;

function rend($diff)
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
