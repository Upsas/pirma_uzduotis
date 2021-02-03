<?php

include './Pattern/Algo.php';
$Pattern = new Algo();
$pattern = ($Pattern->getPattern('mistranslate'));
$position = 0;
// $numbersArray = [];
// print_r($pattern);
($Pattern->getPositionOfPattern('mistranslate', $pattern));
// ($Pattern->populateNumbersArray($pattern));
$Pattern->populatePosition($pattern);
