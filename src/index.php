<?php

use Database\ImportDataFromFile;
use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;

require_once './autoloader.php';

$log = new Log();

$input = new Input($log);
$word = $input->getUserInput();

$patternReader = new PatternReader($word, $log);

$source = trim(strtolower(readline('Enter a source (db or file): ')));

if ($source === 'db') {
    $patternsFromSource = $patternReader->getPatternsFromDb();
} else if ($source === 'file') {
    $patternsFromSource = ($patternReader->getPatterns());
} else {
    echo 'Wrong source:';
    exit;
}
if ($patternReader->checkForDublicates($word) && $source === 'db') {
    $hyphenatedWord = $patternReader->checkForDublicates($word);
    echo 'Patterns: ' . $patternReader->outputPatternsFromDb($word) . PHP_EOL;
} else {
    $pattern = new Pattern($log);
    $patterns = $pattern->populatePositionWithNumber($word, $patternsFromSource);

    $hyphenator = new Hyphenator($patterns);
    $hyphenatedWord = $hyphenator->hyphenate($word);
}
$patternReader->outputPatternsFromDb($word);
if ($source === 'db' && php_sapi_name() == 'cli' && !$patternReader->checkForDublicates($word)) {
    $patternReader->insertSyllableWord($hyphenatedWord);
    $patternReader->insertCorrectPatterns();
}

$output = new Output($log);
$output->outputResult($hyphenatedWord);

$importDataFromFile = new ImportDataFromFile();
// $importDataFromFile->importPatternsToDb('./Assets/tex-hyphenation-patterns.txt');
