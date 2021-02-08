<?php

namespace InputOutput;

use Helpers\RunTime;
use Log\LoggerInterface;

class Output
{

    private $logger;

    public function __construct(LoggerInterface $logger)
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
        $this->logger->info('RunTime: {runTime}', $runTime);
        echo $word . PHP_EOL;
    }
}
