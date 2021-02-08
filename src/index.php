<?php

use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;

require_once './autoloader.php';

$input = new Input();
$word = $input->getUserInput();

$patternReader = new PatternReader($word, new Log());
$patternsFromFile = ($patternReader->getPatterns());

$pattern = new Pattern(new Log());
$patterns = $pattern->populatePositionWithNumber($word, $patternsFromFile);

$hyphenator = new Hyphenator($patterns);
$hyphenatedWord = $hyphenator->hyphenate($word);

$output = new Output(new Log());
$output->outputResult($hyphenatedWord);
