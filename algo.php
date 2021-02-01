<?php

include './index.php';
$string = strtolower('Mistranslate');

foreach ($fa as $s) {
    if (strpos($s, '.') === 0) {
        $starts[] = $s;
    } else if (strpos($s, '.', 1)) {
        $ends[] = $s;
    } else if (strpos($s, 2)) {
        $middle[] = $s;
    }

}
;

foreach ($starts as $start) {

    $haystack = preg_replace('/[0-9]+/', '', $start);

    $haystack = trim($haystack, '.');
    $haystack = trim($haystack);
    // var_dump($start);
    if (str_starts_with($string, $haystack)) {
        echo $start;
    }
    $diff = (array_diff(str_split($string), str_split($start)));
    $a[] = (implode($diff));

}
$b[] = $a;
$key = (array_search($string, $b[0]));
// echo $b[0][$key];
