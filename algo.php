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

    //iteruoji per kiekviena patterno chara,

    for ($i = 0; $i < strlen($allValues); $i++) {
        //jeigu skaiciumi prasideda tai $numbersArray[$position] = $tasSkaicius
        if (is_numeric($allValues[0])) {
            $numbersArray[$position] = $allValues[0];
            break;
        }
        // //jeigu ne tai position+1

        else { $position = $position + 1;}

        //ir checkini kai yra skaiciu imeti ji i $numbersArray[$position] (tik pries mesdamas patikrini ar didesnis skaicius negu dabar yra, jeigu mazesnis tai nelieti ir judi toliau)

        if (is_numeric($allValues[$i]) && !is_numeric($allValues[0])) {

            if ($numbersArray[$position] < $allValues[$i]) {
                $numbersArray[$position] = $allValues[$i];
                break;
            }

        }
    }
    //pabaigoje grazini ta array su skaiciais
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
    $newString = str_split($string);
    for ($i = 0; $i < strlen($string); $i++) {
        if (is_numeric($numbersArray[$i])) {
            $newString[$i * 2] = $numbersArray[$i];

        }
    }
    return $newString;

}

print_r(mergeNumbersWithWord($numbersArray, $string));

function getResult($numbersArray, $string)
{

    return implode(' ', mergeNumbersWithWord($numbersArray, $string));
}
