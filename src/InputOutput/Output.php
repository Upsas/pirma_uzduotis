<?php

namespace InputOutput;


class Output 
{
    public function outputResult($string) {
        echo $string . PHP_EOL;
    }
}
// $end_time = microtime(true);
// echo " time: ", bcsub($end_time, $start_time, 4) . PHP_EOL;
// echo " memory (byte): ", memory_get_peak_usage(true) . PHP_EOL;
