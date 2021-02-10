<?php

use Database\ImportDataToDb;
use Database\Repository;
use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;

require_once './autoloader.php';

$log = new Log();
$repository = new Repository();
$importDataToDb = new ImportDataToDb($repository);
$input = new Input($log);

$word = $input->getUserInput();

$patternReader = new PatternReader($word, $log, $repository);

$source = trim(strtolower(readline('Enter a source (db or file): ')));
if ($source === 'db') {
    $patternsFromSource = $patternReader->getPatterns($source);
} else if ($source === 'file') {
    $fileType = trim(strtolower(readline('Enter a file source (local or new): ')));
    if ($fileType === 'new') {
        $url = trim(readline('Enter url: '));
        $importDataToDb->importPatternsToDb($url);
        $patternsFromSource = $patternReader->getPatterns($source);

    } else if ($fileType === 'local') {
        $patternsFromSource = $patternReader->getPatterns($source);
    } else {
        echo "Wrong input: ";
    }
} else {
    echo 'Wrong source:';
    exit;
}

$output = new Output($word, $log);

if ($repository->checkForDublicates($word) && $source === 'db') {
    $hyphenatedWord = $repository->checkForDublicates($word);
    $string = "Patterns:  %s \n";
    vprintf($string, $output->outputPatternsFromDb($word));
} else {

    $pattern = new Pattern($log);
    $patterns = $pattern->populatePositionWithNumber($word, $patternsFromSource);

    $hyphenator = new Hyphenator($patterns);
    $hyphenatedWord = $hyphenator->hyphenate($word);
    $output->outputResult($hyphenatedWord);
}
if ($source === 'db' && php_sapi_name() == 'cli' && !$repository->checkForDublicates($word)) {
    $importDataToDb->insertSyllableWord($word, $hyphenatedWord);
    $importDataToDb->insertCorrectPatterns($patternsFromSource);
}
