<?php

$start_time = microtime(true);
$string = strtolower('driving');
$numbersArray = [];
$position = 0;
// visa logika atskiram faile, klase tik gauna apdorotus duomenis
foreach ($fa as $value) {

    $haystack = preg_replace('/[0-9]+/', '', $value);
    $haystack = trim($haystack, '.');
    $haystack = trim($haystack);
    $value = trim($value);

    if (strpos($value, '.') === 0 && str_starts_with($string, $haystack)) {

        $allValues[] = $value;

    } else if (strpos($string, $haystack) !== false && strpos($value, '.') !== 0) {

        $allValues[] = $value;

    }if (strpos($value, '.') > 0 && str_ends_with($string, substr($haystack, 0, -1))) {

        $allValues[] = $value;

    }
}
$allValues = array_values(array_unique($allValues));

// +1 visa logika atskiram faile, klase tik gauna apdorotus duomenis

function stripNumbers($allValues)
{
    //grazina patterna be skaiciu (ir tasku gal)
    return preg_replace('/[0-9]+/', '', $allValues);

}

function getPositionOfPattern($string, $allValues)
{
    $strippedPattern = stripNumbers($allValues);

    if (strpos($strippedPattern, '.') === 0) {
        return intval(strpos($string, trim($strippedPattern . ' ')));
    } else if ((substr($strippedPattern, -1) === '.')) {
        return intval(strpos($string, trim($strippedPattern, '.')));
    } else {
        return intval(strpos($string, $strippedPattern));
    }
    if (!strpos($string, $strippedPattern)) {
        return -1;
    }

};
// +1 visa logika atskiram faile, klase tik gauna apdorotus duomenis

function populateNumbersArray($numbersArray, $position, $allValues)
{

    for ($i = 0; $i < strlen($allValues); $i++) {
        if (is_numeric($allValues[$i])) {
            if (isset($numbersArray[$position])) {
                if ($numbersArray[$position] < $allValues[$i]) {
                    $numbersArray[$position] = $allValues[$i];
                }
            } else {
                $numbersArray[$position] = $allValues[$i];
            }
        } else {
            $position = $position + 1;
        }
    }
    return ($numbersArray);

}

foreach ($allValues as $pattern) {
    $position = getPositionOfPattern($string, $pattern);
    if ($position > -1) {
        $numbersArray = populateNumbersArray($numbersArray, $position, $pattern);
    }
}
//galbut i output klase ir jos logika

function mergeNumbersWithWord($numbersArray, $string)
{
    $newString = implode(" ", str_split($string, 1));
    $newString = str_split($newString);

    for ($i = 0; $i < strlen($string); $i++) {
        if (is_numeric($numbersArray[$i])) {
            $newString[$i * 2 - 1] = $numbersArray[$i];
        }
    }
    return ($newString);

}
