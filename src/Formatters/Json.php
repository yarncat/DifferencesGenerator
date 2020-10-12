<?php

namespace Differ\Formatters\Json;

function renderJson($tree)
{
    return json_encode($tree) . "\n";
}
