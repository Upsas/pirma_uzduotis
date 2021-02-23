<?php

declare(strict_types=1);

namespace App\Pattern;

class Hyphenator
{
    /**
    * @var Pattern[]
    */

    private array $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    /**
     * Checks if pattern sub-string mathces word
     *
     * @param string $word
     * @param Pattern $pattern
     * @return int $position
     */

    private function getPositionOfPattern(string $word, Pattern $patterns): int
    {
        $pattern = trim($patterns->stripNumbers());
        if (strpos($pattern, ' .') === 0) {
            return intval(strpos($word, trim($pattern)));
        } elseif ((substr($pattern, -1) === '.')) {
            return intval(strpos($word, trim($pattern, '.')));
        } else {
            return intval(strpos($word, $pattern));
        }
        if (!strpos($word, $pattern)) {
            return -1;
        }
    }

    /**
     * Get specific patterns using word
     *
     * @param string $word
     * @return Pattern[]
     */

    public function getSelectedPatterns(string $word): ?array
    {
        foreach ($this->patterns as $pattern) {
            $needle = preg_replace('/[0-9\s.]+/', '', $pattern->getPattern());
            $value = trim($pattern->getPattern());
            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {
                $selectedPatterns[] = $pattern;
            } elseif (preg_match('/' . $needle . '$/', $word) && preg_match('/\.$/', $value)) {
                $selectedPatterns[] = $pattern;
            } elseif (preg_match('/' . $needle . '/', $word) && !preg_match('/\./', $value)) {
                $selectedPatterns[] = $pattern;
            }
        }
        return $selectedPatterns;
    }

    /**
     * Get array of numbers
     *
     * @param array $numbersArray
     * @param  $pattern
     * @param int $position
     * @return string[]
     */

    private function populateNumbersArray(array $numbersArray, int $position, string $pattern): array
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
     * @param string $word
     * @param Pattern[] $patterns
     * @return string[]
     */

    private function populatePositionWithNumber(string $word, array $patterns): array
    {
        $numbersArray = [];
        if (!empty($patterns)) {
            foreach ($patterns as $pattern) {
                $position = $this->getPositionOfPattern($word, $pattern);
                if ($position > -1) {
                    $numbersArray = $this->populateNumbersArray($numbersArray, $position, $pattern->getPattern());
                }
            }
        }
        return $numbersArray;
    }

    /**
     * Create a new merges numbers with word.
     *
     * Split imploded word and merge numbers with word using for loop.
     *
     * @param string $word
     * @param array $numbersArray
     * @return string[]
     */

    private function mergeNumbersWithWord(string $word, array $numbersArray): array
    {
        $newWord = implode(" ", str_split($word, 1));
        $newWord = str_split($newWord);
        for ($i = 0; $i < strlen($word); $i++) {
            if (!empty($numbersArray[$i])) {
                if (is_numeric($numbersArray[$i])) {
                    $newWord[$i * 2 - 1] = $numbersArray[$i];
                }
            }
        }
        return $newWord;
    }

    /**
     * Creates hyphenated word.
     *
     * Implode merged word and trim whitespaces. If last symbol is number remove it.
     * Replace odd numbers with '-' and remove the rest numbers.
     *
     * @param string $word
     * @return string $word
     */

    public function hyphenate(string $word): string
    {
        $selectedPatterns = $this->getSelectedPatterns($word);
        $numbersArray = $this->populatePositionWithNumber($word, $selectedPatterns);
        $word = implode('', $this->mergeNumbersWithWord($word, $numbersArray));
        $word = str_replace(' ', '', $word);
        if (is_numeric(substr($word, -1, 1))) {
            $word = substr($word, 0, -1);
        }
        $word = preg_replace('/[1,3,5]+/', '-', $word);
        return preg_replace('/[0-9]+/', '', $word);
    }
}
