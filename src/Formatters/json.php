<?php

namespace Differ\Formatters\json;

function rend($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
