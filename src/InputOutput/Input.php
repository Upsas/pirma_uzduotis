<?php

namespace InputOutput;

class Input
{
    /**
     * Getting data from user
     *
     * Validate data from input (regex for matching letters && not empty)
     *
     * @param  string $word
     * @return $word
     */

    public function getUserInput()
    {
        $word = strtolower(readline('Enter a word: '));
        $word = strval($word);

        if (preg_match_all('/[a-z]+/', $word) && !empty($word)) {
            return $word;
        } else {
            echo 'wrong input';
        }

    }

}
