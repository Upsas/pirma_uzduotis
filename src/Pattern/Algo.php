<?php

class Algo
{
    public $start_time;
    public $string = ('mistranslate');
    public $numbersArray = [];
    public $position = 0;
    public $file = "./Assets/tex-hyphenation-patterns.txt";

    public function getDataFromFile()
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

            if (strpos($value, '.') === 0 && str_starts_with($string, $haystack)) {

                $pattern[] = $value;

            } else if (strpos($string, $haystack) !== false && strpos($value, '.') !== 0) {

                $pattern[] = $value;

            }if (strpos($value, '.') > 0 && str_ends_with($string, substr($haystack, 0, -1))) {

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

    public function getPositionOfPattern($string, $pattern)
    {
        // Veliau pasiziureti

        ($strippedPattern = $this->stripNumbers($pattern));

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

    public function populatePosition($pattern)
    {
        echo $pattern;
        foreach ($pattern as $pattern) {
            echo $position = $this->getPositionOfPattern($this->string, $pattern);
        }
        // if ($position > -1) {
        //     print_r(($numbersArray = $this->populateNumbersArray($this->numbersArray, $position, $pattern)));
        // }
    }
    public function populateNumbersArray($numbersArray, $position, $pattern)
    {

        for ($i = 0; $i < strlen($pattern[$i]); $i++) {
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

    public function mergeNumbersWithWord($numbersArray, $string)
    {
        $newString = implode(" ", str_split($string, 1));
        $newString = str_split($newString);

        for ($i = 0; $i < strlen($string); $i++) {
            if (is_numeric($numbersArray[$i])) {
                $newString[$i * 2 - 1] = $numbersArray[$i];
            }
        }
        return ($newString);

    }

}
