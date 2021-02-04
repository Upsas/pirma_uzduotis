<?php

namespace Pattern;

class Hyphenator
{
    public $numbersArray;
    public function __construct($numbersArray)
    {
        $this->numbersArray = $numbersArray;
    }
    public function mergeNumbersWithWord($string)
    {
        $newString = implode(" ", str_split($string, 1));
        $newString = str_split($newString);
        for ($i = 0; $i < strlen($string); $i++) {

            if (!empty($this->numbersArray[$i])) {

                if (is_numeric($this->numbersArray[$i])) {

                    $newString[$i * 2 - 1] = $this->numbersArray[$i];

                }
            }
        }
        return ($newString);
    }

    public function hyphenate($string)
    {

        $string = implode('', $this->mergeNumbersWithWord($string));
        $string = str_replace(' ', '', $string);

        if (is_numeric(substr($string, -1, 1))) {

            $string = substr($string, 0, -1);
        }
        $string = preg_replace('/[1,3,5]+/', '-', $string);

        return preg_replace('/[0-9]+/', '', $string);
    }
}
