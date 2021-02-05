<?php

namespace Pattern;

use Helpers\RunTime;

class PatternReader
{

    /**
     * A list of numbers
     *
     * @var array
     */

    protected $numbersArray = [];

    /**
     * File with all patterns
     *
     * @var url
     */

    protected $file = "./Assets/tex-hyphenation-patterns.txt";

    public function __construct($word)
    {
        /**
         * Create a new word instance.
         *
         * @param string $word
         * @return void
         */

        $this->word = $word;
    }

    public function getDataFromFile()
    {
        /**
         * Checks if file exists
         *
         * @param $this->file
         * @return array $this->file
         */

        if (file_exists($this->file)) {
            return file($this->file);
        } else {
            return false;
        }
    }

    public function getPatterns()
    {
        /**
         * Get specific patterns using word
         *
         * @param string $word
         * @param array $file
         * @return array $pattern
         */

        $word = $this->word;
        $file = $this->getDataFromFile();

        foreach ($file as $value) {

            $needle = preg_replace('/[0-9]+/', '', $value);
            $needle = trim($needle, '.');
            $needle = trim($needle);
            $value = trim($value);
            if (strpos($value, '.') === 0 && strpos($word, $needle) === 0) {

                $pattern[] = $value;

            } else if (strpos($word, $needle) !== false && strpos($value, '.') !== 0) {

                $pattern[] = $value;
            } else if (strpos($value, '.') > 0 && strpos($word, trim($needle, '.'), 3)) {
                $pattern[] = $value;
            }
        }
        $pattern = array_values(array_unique($pattern));
        return $pattern;
    }

    public function stripNumbers($pattern)
    {
        /**
         * Strip all numbers from pattern
         *
         * @param array $pattern
         * @return array $pattern
         */

        return preg_replace('/[0-9]+/', '', $pattern);

    }

    private function getPositionOfPattern($word, $pattern)
    {
        /**
         * Checks if pattern sub-string mathces word
         *
         * @param string $word
         * @param array $pattern
         * @return int $position
         */

        $strippedPattern = $this->stripNumbers($pattern);

        if (strpos($strippedPattern, '.') === 0) {

            return intval(strpos($word, trim($strippedPattern . ' ')));

        } elseif ((substr($strippedPattern, -1) === '.')) {

            return intval(strpos($word, trim($strippedPattern, '.')));

        } else {

            return intval(strpos($word, $strippedPattern));
        }
        if (!strpos($word, $strippedPattern)) {

            return -1;
        }

    }

    public function populateNumbersArray($numbersArray, $position, $pattern)
    {

        /**
         * Get array of numbers
         *
         * @param array $numbersArray
         * @param array $pattern
         * @param int $position
         * @return $numbersArray
         */

        for ($i = 0; $i < strlen($pattern); $i++) {
            $newI = $i;
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

    public function populatePositionWithNumber()
    {
        RunTime::timeStart();
        /**
         * Get array [position] => number
         *
         * @param array $numbersArray
         * @param string $word
         * @param array $pattern
         * @param int $position
         * @return $this->numbersArray
         */

        foreach ($this->getPatterns($this->word) as $test) {

            $position = $this->getPositionOfPattern($this->word, $test);

            if ($position > -1) {

                $this->numbersArray = $this->populateNumbersArray($this->numbersArray, $position, $test);
            }
        }
        RunTime::timeEnd();
        RunTime::getRunTime();
        return ($this->numbersArray);
    }
}
