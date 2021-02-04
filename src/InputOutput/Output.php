<?php

namespace InputOutput;

use Pattern\Algo;

class Output extends Algo
{
    public function getResult($numbersArray)
    {
        $string = implode('', $this->mergeNumbersWithWord($numbersArray, $this->string));
        $string = str_replace(' ', '', $string);

        if (is_numeric(substr($string, -1, 1))) {

            $string = substr($string, 0, -1);
        }
        $string = preg_replace('/[1,3,5]+/', '-', $string);

        echo preg_replace('/[0-9]+/', '', $string) . PHP_EOL;

    }
}
// $end_time = microtime(true);
// echo " time: ", bcsub($end_time, $start_time, 4) . PHP_EOL;
// echo " memory (byte): ", memory_get_peak_usage(true) . PHP_EOL;
