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

    public function outputPatternsFromDb($word)
    {
        $sql = "SELECT `id` FROM `syllable_words` WHERE `syllable_word` = ?";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$word]);
        $values = $prepare->fetch();
        if (!empty($values)) {
            if (count($values) > 0) {
                $id = $values['id'];
            }
        }

        $patterns = "SELECT `correct_pattern` FROM `correct_patterns` WHERE `sylable_word_id` = $id";
        $t = $this->connect()->query($patterns);
        $correctPatterns = $t->fetch();
        if (!empty($correctPatterns)) {
            return $correctPatterns['correct_pattern'];
        }

    }
}
