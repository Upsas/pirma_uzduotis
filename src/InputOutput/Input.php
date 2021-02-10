<?php

namespace InputOutput;

use Helpers\RunTime;
use Log\Log;

class Input
{

    private $log;

    public function __construct(Log $log)
    {
        $this->logger = $log;
    }

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
        RunTime::timeStart();
        $word = strtolower(readline('Enter a word: '));
        $word = strval(trim($word));

        if (preg_match_all('/[a-z]+/', $word) && !empty($word)) {

            $context['word'] = $word;
            $this->logger->info('Word: {word}', $context);
            return $word;

        } else {
            echo 'wrong input' . PHP_EOL;
            exit;
        }

    }

}
