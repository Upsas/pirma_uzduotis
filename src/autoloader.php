<?php

spl_autoload_register('classLoader');
function classLoader($class)
{
    $file = str_replace("\\", '/', $class) . '.php';
    if (!file_exists($file)) {
        return false;
    } else {
        include_once $file;
    }
}
