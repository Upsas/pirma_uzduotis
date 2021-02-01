<?php

$file = './tex-hyphenation-patterns.txt';

if (file_exists($file)) {
    $fo = fopen($file, 'r');
    $fr = fread($fo, filesize($file));
    $fa = file($file);
} else {
    echo 'File doesnt exist';
}
