<?php

declare(strict_types=1);

namespace App\InputOutput;

use App\Helpers\RunTime;
use App\Log\Log;

class Input
{
    private Log $log;

    /**
     * @param  Log $log
     */

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
     * @return string $word
     */

    public function getUserInput(): string
    {
        RunTime::timeStart();
        $word = strtolower(readline('Enter a word: '));
        $word = strval(trim($word));

        if (preg_match_all('/[a-z]+/', $word) && !empty($word)) {
            $context['word'] = $word;
            $this->logger->info('Word: {word}', $context);
            return (string) $word;
        } else {
            echo 'wrong input' . PHP_EOL;
            exit;
        }
    }
}
