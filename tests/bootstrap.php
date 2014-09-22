<?php

require __DIR__ . '/../vendor/autoload.php';

if ( ! function_exists('fixtures_path') )
{
    function fixtures_path($path = '')
    {
        return __DIR__ . '/fixtures/' . $path;
    }
}