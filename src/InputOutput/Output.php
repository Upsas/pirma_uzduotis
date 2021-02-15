<?php

namespace InputOutput;

use Database\DatabaseConnection;
use Helpers\RunTime;
use Log\Log;

class Output extends DatabaseConnection
{

    private $logger;
    public $word;
    public function __construct($word, Log $logger)
    {
        $this->word = $word;
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
