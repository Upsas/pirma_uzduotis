<?php

namespace Pattern;

class Hyphenator
{

    public function __construct($numbersArray)
    {
        /**
         * Create a new $numbersArray instance.
         *
         * @param array $numbersArray
         * @return void
         */

        $this->numbersArray = $numbersArray;
    }
    public function mergeNumbersWithWord($word)
    {
        /**
         * Create a new merges numbers with word.
         *
         * Split imploded word and merge numbers with word using for loop.
         *
         *
         * @param string $word
         * @param array $numbersArray
         * @return array $newWord
         */

        $newWord = implode(" ", str_split($word, 1));
        $newWord = str_split($newWord);
        for ($i = 0; $i < strlen($word); $i++) {

            if (!empty($this->numbersArray[$i])) {
                if (is_numeric($this->numbersArray[$i])) {
                    $newWord[$i * 2 - 1] = $this->numbersArray[$i];
                }
            }
        }

        return $newWord;
    }

    public function hyphenate($word)
    {
        /**
         * Creates hyphenated word.
         *
         * Implode merged word and trim whitespaces. If last symbol is number remove it.
         * Replace odd numbers with '-' and remove the rest numbers.
         *
         * @param string $word
         * @return string $word
         */

        $word = implode('', $this->mergeNumbersWithWord($word));
        $word = str_replace(' ', '', $word);

        if (is_numeric(substr($word, -1, 1))) {
            $word = substr($word, 0, -1);
        }
        $word = preg_replace('/[1,3,5]+/', '-', $word);

        return preg_replace('/[0-9]+/', '', $word);
    }
}
