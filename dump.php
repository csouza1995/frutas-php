<?php

if (!function_exists('dd')) {
    function dd($var): void
    {
        dump($var);
        die();
    }
}

if (!function_exists('dump')) {
    function dump($var): void
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}
