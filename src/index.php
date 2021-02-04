<?php

use InputOutput\Input;
use InputOutput\Output;
use Pattern\Algo;

include './Pattern/Algo.php';
include './InputOutput/Output.php';
include './InputOutput/Input.php';

$Input = new Input();

$string = $Input->getUserInput();

$Pattern = new Algo($string);

$Output = new Output($string);

$pattern = $Pattern->getPattern($string);

$numbersArray = ($Pattern->populatePositionWithNumber($pattern));

$Output->getResult($numbersArray);
