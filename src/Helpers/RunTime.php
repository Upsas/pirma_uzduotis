<?php

namespace Helpers;

class RunTime
{
    static $start;
    static $end;

    public static function timeStart()
    {
        self::$start = microtime(true);
    }

    public static function timeEnd()
    {
        self::$end = microtime(true);
    }

    public static function timeCalc()
    {
        return (self::$end - self::$start);
    }

    public static function getRunTime()
    {
        return ('It took ' . number_format(self::timeCalc(), 2) . ' seconds to run a code block') . PHP_EOL;
    }

}
