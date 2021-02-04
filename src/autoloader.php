<?php
spl_autoload_register('classLoader');
echo __DIR__;
function classLoader($class)
{
    $path = "./Pattern/" . ($class) . ".php";
    if (!file_exists($path)) {
        return false;
    }
    include_once $path;
}
