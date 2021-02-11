<?php

namespace Pattern;

use Log\LoggerInterface;

class Pattern
{
    private $logger;
    /**
     * A list of numbers
     *
     * @var array
     */

    protected $numbersArray = [];

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Strip all numbers from pattern
     *
     * @param array $pattern
     * @return array $pattern
     */

    public function stripNumbers($pattern)
    {
        return preg_replace('/[0-9]+/', '', $pattern);
    }

    /**
     * Checks if pattern sub-string mathces word
     *
     * @param string $word
     * @param array $pattern
     * @return int $position
     */

    private function getPositionOfPattern($word, $pattern)
    {

        $strippedPattern = $this->stripNumbers($pattern);
        if (strpos($strippedPattern, ' .') === 0) {

            return intval(strpos($word, trim($strippedPattern)));

        } elseif ((substr($strippedPattern, -1) === '.')) {

            return intval(strpos($word, trim($strippedPattern, '.')));

        } else {

            return intval(strpos($word, $strippedPattern));
        }
        if (!strpos($word, $strippedPattern)) {

            return -1;
        }
    }

    /**
     * Get array of numbers
     *
     * @param array $numbersArray
     * @param array $pattern
     * @param int $position
     * @return $numbersArray
     */

    public function populateNumbersArray($numbersArray, $position, $pattern)
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

    /**
     * Get array [position] => number
     *
     * @param array $numbersArray
     * @param string $word
     * @param array $pattern
     * @param int $position
     * @return $this->numbersArray
     */

    public function populatePositionWithNumber($word, $patterns)
    {
        $this->numbersArray = [];
        if (!empty($patterns)) {
            foreach ($patterns as $pattern) {
                $position = $this->getPositionOfPattern($word, $pattern);

                if ($position > -1) {
                    $this->numbersArray = $this->populateNumbersArray($this->numbersArray, $position, $pattern);
                }
            }
        }
        return $this->numbersArray;
    }
}
