<?php

use Helpers\RunTime;
use InputOutput\Input;
use InputOutput\Output;
use Pattern\Hyphenator;
use Pattern\PatternReader;
require_once './autoloader.php';
RunTime::timeStart();
$Input = new Input();
$word = $Input->getUserInput();

$PatternReader = new PatternReader($word);
$pattern = $PatternReader->populatePositionWithNumber();

$Hyphenator = new Hyphenator($pattern);
$word = $Hyphenator->hyphenate($word);

$Output = new Output();
$Output->outputResult($word);
