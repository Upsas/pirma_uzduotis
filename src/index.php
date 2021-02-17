<?php

use Repositories\PatternsRepository;
use Repositories\QueryBuilder;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

require_once './autoloader.php';

$app = new App();
// // echo 'aaa';


// ($queryBuilder = new QueryBuilder());
// // $a =($queryBuilder->select('word')->from('words')->where(['word', 'mistranslate'])->get());

// // foreach ($a as $b) {
// //     echo $b->word;
// // }

// $wordsRepository = new WordsRepository();
// ($wordsRepository->getAllWordsFromDb());

$patterns = new PatternsRepository();
// var_dump($patterns->getPatternsFromDb());
