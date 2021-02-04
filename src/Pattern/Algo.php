<?php

namespace Pattern;

class Algo
{
    public $start_time;
    public $string;
    public $numbersArray = [];
    public $position = 0;
    private $file = "./Assets/tex-hyphenation-patterns.txt";

    public function __construct($string)
    {
        $this->string = $string;
    }

    private function getDataFromFile()
    {
        if (file_exists($this->file)) {

            return file($this->file);

        } else {

            return false;
        }
    }

    public function getPattern($string)
    {

        $fa = $this->getDataFromFile();

        foreach ($fa as $value) {

            $haystack = preg_replace('/[0-9]+/', '', $value);
            $haystack = trim($haystack, '.');
            $haystack = trim($haystack);
            $value = trim($value);

            if (strpos($value, '.') === 0 && strpos($string, $haystack) === 0) {

                $pattern[] = $value;

            } else if (strpos($string, $haystack) !== false && strpos($value, '.') !== 0) {

                $pattern[] = $value;
            }

            if (strpos($value, '.') > 0 && strpos($string, substr($haystack, 0, -1) === -1)) {

                $pattern[] = $value;

            }
        }
        $pattern = array_values(array_unique($pattern));
        return $pattern;
    }

    public function stripNumbers($pattern)
    {

        return preg_replace('/[0-9]+/', '', $pattern);

    }

    private function getPositionOfPattern($string, $pattern)
    {
        $strippedPattern = $this->stripNumbers($pattern);

        if (strpos($strippedPattern, '.') === 0) {

            return intval(strpos($string, trim($strippedPattern . ' ')));

        } elseif ((substr($strippedPattern, -1) === '.')) {

            return intval(strpos($string, trim($strippedPattern, '.')));

        } else {

            return intval(strpos($string, $strippedPattern));
        }
        if (!strpos($string, $strippedPattern)) {

            return -1;
        }

    }

    private function populateNumbersArray($numbersArray, $position, $pattern)
    {
        for ($i = 0; $i < strlen($pattern); $i++) {

            if (is_numeric($pattern[$i])) {

                if (isset($numbersArray[$position])) {

                    if ($numbersArray[$position] < $pattern[$i]) {

                        $numbersArray[$position] = $pattern[$i];
                    }
                } else {

                    $numbersArray[$position] = $pattern[$i];
                }
            } else {

                $position = $position + 1;
            }
        }
        return $numbersArray;
    }

    public function populatePositionWithNumber($pattern)
    {
        foreach ($pattern as $test) {

            $position = $this->getPositionOfPattern($this->string, $test);

            if ($position > -1) {

                $this->numbersArray = $this->populateNumbersArray($this->numbersArray, $position, $test);
            }
        }
        return $this->numbersArray;
    }
    protected function mergeNumbersWithWord($numbersArray)
    {
        $newString = implode(" ", str_split($this->string, 1));
        $newString = str_split($newString);
        for ($i = 0; $i < strlen($this->string); $i++) {

            if (!empty($numbersArray[$i])) {

                if (is_numeric($numbersArray[$i])) {

                    $newString[$i * 2 - 1] = $numbersArray[$i];

                }
            }
        }
        return ($newString);
    }

}
