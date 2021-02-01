<?php

include './index.php';
$string = strtolower('Mistranslate');

$newString = implode(" ", str_split($string, 1));
$newString = str_split($newString);
print_r($newString);
foreach ($fa as $value) {

    $haystack = preg_replace('/[0-9]+/', '', $value);
    $haystack = trim($haystack, '.');
    $haystack = trim($haystack);
    $value = trim($value);

    if (strpos($value, '.') === 0 && str_starts_with($string, $haystack)) {
        echo strpos($string, $haystack);

    } else if (strpos($string, $haystack) !== false && strpos($value, '.') !== 0) {
        echo strpos($string, $haystack);

        $abc[] = $value;
    }if (strpos($value, '.', 1) && str_ends_with($string, substr($haystack, 0, -1))) {

    }
}
