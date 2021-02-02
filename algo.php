<?php

include './index.php';
$string = strtolower('Mistranslate');
$numbersArray = [];

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
    print_r($strippedPattern); //nuema skaicius nuo patterno, jeigu yra taskai tai checkina kad butu pradzia/pabaiga zodzio
    //jeigu randa patterna tai grazina int su pozicija, jeigu ne -1 arba exceptiona
    if (strpos($strippedPattern, '.') === 0) {
        return strpos($string, $strippedPattern);
    } else if ((strpos($strippedPattern, -1) === '.')) {
        return strpos($string, $strippedPattern);
    } else {
        return strpos($string, $strippedPattern);
    }
    if (!strpos($string, $strippedPattern)) {
        return false;
    }

};

foreach ($allValues as $pattern) {
    $position = getPositionOfPattern($string, $pattern);
    if ($position > -1) {
        $numbersArray = populateNumbersArray($numbersArray, $position, $pattern);
    }
}

$result = getResult();
function populateNumbersArray($numbersArray, $position, $allValues)
{

    //iteruoji per kiekviena patterno chara,
    foreach ($allValues as $patter) {
        //jeigu skaiciumi prasideda tai $numbersArray[$position] = $tasSkaicius
        if (is_numeric($patter[0])) {
            $numbersArray[$position] = $patter[0];
        } else { $position = $position + 1;} //jeigu ne tai position+1
        //ir checkini kai yra skaiciu imeti ji i $numbersArray[$position] (tik pries mesdamas patikrini ar didesnis skaicius negu dabar yra, jeigu mazesnis tai nelieti ir judi toliau)
        for ($i = 0; $i < strlen($patter); $i++) {
            if (is_numeric($patter[$i])) {
                // echo ' pos: ' . $position . ' ';
            }
        }
    }

    return $numbersArray;
    //pabaigoje grazini ta array su skaiciais
}
populateNumbersArray($numbersArray, $position, $allValues);
// print_r(populateNumbersArray($numbersArray, $position, $allValues));
function mergeNumbersWithWord($numbersArray, $string)
{
    // return array_merge($numbersArray, str_split($string));
}
// echo mergeNumbersWithWord($numbersArray, $string);
function getResult()
{
    //callina mergeNumbers ir pakeicia skaicius i - jeigu reikia, jeigu ne tai istrina skaiciu
}
