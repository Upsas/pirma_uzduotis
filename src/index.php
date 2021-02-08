<?php

use Helpers\RunTime;
use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Log\NullLogger;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;

require_once './autoloader.php';

RunTime::timeStart();

$input = new Input();
$word = $input->getUserInput();

$patternReader = new PatternReader($word, new NullLogger());
$patternsFromFile = ($patternReader->getPatterns());

$pattern = new Pattern(new NullLogger());
$patterns = $pattern->populatePositionWithNumber($word, $patternsFromFile);

$hyphenator = new Hyphenator($patterns);
$hyphenatedWord = $hyphenator->hyphenate($word);

$output = new Output(new NullLogger());
$output->outputResult($hyphenatedWord);

RunTime::timeEnd();

$file = new SplFileObject('./Log/log.txt', 'a+');

$dataLogs['date'] = date('Y H:i:s', $file->getATime());
$dataLogs['word'] = $word;
$dataLogs['patterns'] = implode(' ', $patternsFromFile);
$dataLogs['hypernatedWord'] = $hyphenatedWord;
$dataLogs['runTime'] = RunTime::getRunTime();

$log = new Log();

foreach ($dataLogs as $key => $dataLog) {
    $data[] = PHP_EOL . $key . ': ' . $log->interpolate('{' . $key . '}', $dataLogs);
}
$file->fwrite(implode(' ', $data) . PHP_EOL);
