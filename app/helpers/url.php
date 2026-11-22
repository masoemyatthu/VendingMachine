<?php

function url($path = '')
{
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    if ($basePath === '/' || $basePath === '\\') {
        $basePath = '';
    }

    return $basePath . '/' . ltrim($path, '/');
}