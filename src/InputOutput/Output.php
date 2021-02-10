<?php

namespace InputOutput;

use Helpers\RunTime;
use Log\Log;

class Output
{

    private $logger;

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

    public function outputResult($word)
    {
        RunTime::timeEnd();
        $runTime['runTime'] = RunTime::getRunTime();
        $context['word'] = $word;
        $this->logger->info('HyphenatedWord: {word}', $context);
        $this->logger->info('RunTime: {runTime}', $runTime);
        echo $word . PHP_EOL;
    }
}
