<?php

namespace Differ\Formatters\json;

function render($ast)
{
    return json_encode($ast, JSON_PRETTY_PRINT);
}
