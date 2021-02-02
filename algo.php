<?php

include './index.php';
$string = strtolower('Mistranslate');

$newString = implode(" ", str_split($string, 1));
$newString = str_split($newString);
foreach ($fa as $value) {

    $haystack = preg_replace('/[0-9]+/', '', $value);
    $haystack = trim($haystack, '.');
    $haystack = trim($haystack);
    $value = trim($value);

    if (strpos($value, '.') === 0 && str_starts_with($string, $haystack)) {
        $value = trim($value, '.');
        $pos = strpos($string, $haystack) * 2;
        $value = implode(" ", str_split($value, 1));
        $arr[$pos] = str_split($value);
        $s[$pos] = (array_filter(str_split($value), 'is_numeric'));
        print_r($s);
    }if (strpos($string, $haystack) !== false && strpos($value, '.') !== 0) {

        $value = trim($value, '.');
        $pos = strpos($string, $haystack) * 2;
        $value = implode(" ", str_split($value, 1));
        $arr[$pos] = str_split($value);
        $asf = array_filter(str_split($value), 'is_numeric');
        $m[$pos][] = (array_filter(str_split($value), 'is_numeric'));

    }if (strpos($value, '.') > 0 && str_ends_with($string, substr($haystack, 0, -1))) {

        $haystack = substr($haystack, 0, -1);
        $pos = strpos($string, $haystack) * 2;
        $value = implode(" ", str_split($value, 1));
        $arr[$pos] = str_split($value);
        $e[$pos] = (array_filter(str_split($value), 'is_numeric'));

    }
}

// print_r($s);
print_r($m);
// print_r($e);
