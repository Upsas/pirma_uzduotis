<?php

use InputOutput\Input;
use InputOutput\Output;
use Pattern\Hyphenator;
use Pattern\PatternReader;

require_once './autoloader.php';

$Input = new Input();
$string = $Input->getUserInput();

$PatternReader = new PatternReader('mistranslate');
($pattern = $PatternReader->populatePositionWithNumber());

$Hyphenator = new Hyphenator($pattern);
$string = $Hyphenator->hyphenate($string);

$Output = new Output();
$Output->outputResult($string);
