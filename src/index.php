<?php

use InputOutput\Input;
use InputOutput\Output;
use Pattern\Hyphenator;
use Pattern\PatternReader;

include './Pattern/Hyphenator.php';
include './InputOutput/Output.php';
include './InputOutput/Input.php';
include './Pattern/PatternReader.php';

$Input = new Input();
$string = $Input->getUserInput();
$PatternReader = new PatternReader($string);
$pattern = $PatternReader->populatePositionWithNumber();

$Hyphenator = new Hyphenator($pattern);
$string = $Hyphenator->hyphenate($string);
$Output = new Output();
$Output->outputResult($string);