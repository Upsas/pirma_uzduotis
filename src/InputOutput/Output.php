<?php

declare(strict_types=1);

namespace App\InputOutput;

use App\Database\DatabaseConnection;
use App\Helpers\RunTime;
use App\Log\Log;

class Output extends DatabaseConnection
{
    private log $logger;

    /**
     * @param  Log $logger
     */

    public function __construct(Log $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Getting data from hypernate class and outputing for user
     *
     * @param  string $word
     * @return void
     */

    public function outputResult(string $word): void
    {
        RunTime::timeEnd();
        $runTime['runTime'] = RunTime::getRunTime();
        $context['word'] = $word;
        $this->logger->info('HyphenatedWord: {word}', $context);
        $this->logger->info('RunTime: {runTime}', $runTime);
        echo $word . PHP_EOL;
    }
}
