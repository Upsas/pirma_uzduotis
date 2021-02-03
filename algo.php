<?php
$start_time = microtime(true);
include './index.php';
$string = strtolower('driving');
$numbersArray = [];
$position = 0;
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
function stripNumbers($allValues)
{
    //grazina patterna be skaiciu (ir tasku gal)
    return preg_replace('/[0-9]+/', '', $allValues);

}

function getPositionOfPattern($string, $allValues)
{
    $strippedPattern = stripNumbers($allValues);
    //nuema skaicius nuo patterno, jeigu yra taskai tai checkina kad butu pradzia/pabaiga zodzio
    //jeigu randa patterna tai grazina int su pozicija, jeigu ne -1 arba exceptiona

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

function getResult($numbersArray, $string)
{

    $string = implode('', mergeNumbersWithWord($numbersArray, $string));
    $string = str_replace(' ', '', $string);
    if (is_numeric(substr($string, -1, 1))) {
        $string = substr($string, 0, -1);
    }
    $string = preg_replace('/[1,3,5]+/', '-', $string);
    return $string = preg_replace('/[0-9]+/', '', $string);

}
echo getResult($numbersArray, $string);

$end_time = microtime(true);
echo " time: ", bcsub($end_time, $start_time, 4) . PHP_EOL;
echo " memory (byte): ", memory_get_peak_usage(true) . PHP_EOL;
