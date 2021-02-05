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

        return (round(self::$end - self::$start, 5));
    }

    public static function getRunTime()
    {
        echo ('It took ' . self::timeCalc() . ' seconds to run a code block') . PHP_EOL;
    }

}
