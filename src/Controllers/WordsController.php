<?php

namespace Controllers;

use Pattern\Hyphenator;
use Repositories\PatternsRepository;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

class WordsController
{

    public function getAllHyphenatedWords()
    {
        $wordsRepository = new WordsRepository();
        header('Content-Type: application/json');
        echo json_encode($wordsRepository->getAllHyphenatedWordsFromDb());

    }
    public function insertDataToDb()
    {
        if (!empty($word = $_POST['word'])) {

            $wordsRepository = new WordsRepository();
            $patternRepisotry = new PatternsRepository();
            $patterns = $patternRepisotry->getPatternsFromDb();
            $hyphenator = new Hyphenator($patterns);
            if (empty($wordsRepository->checkForDuplicates($word))) {
                $wordsRepository->addWords($word, $hyphenator->hyphenate($word));
                $this->addRelationsToDb($word);
            } else {echo 'Word already exists';}
        } else {echo 'Empty data';}
    }

    public function editData()
    {

        parse_str(file_get_contents("php://input"), $data);
        $word = $data['word'];
        $newWord = $data['newWord'];
        $wordsRepository = new WordsRepository();
        $duplicate = $wordsRepository->checkForDuplicates($word);
        $newWordDuplicate = $wordsRepository->checkForDuplicates($newWord);
        if (isset($duplicate) && !empty($word) && !empty($newWord) && empty($newWordDuplicate)) {
            $patternRepisotry = new PatternsRepository();
            $patterns = $patternRepisotry->getPatternsFromDb();
            $hyphenator = new Hyphenator($patterns);
            $newHyphenatedWord = $hyphenator->hyphenate($newWord);
            $id = $wordsRepository->getWordId($word);
            $this->deleteRelationFromDb($word);
            $wordsRepository->updateWord($newWord, $newHyphenatedWord, $id);
            $this->addRelationsToDb($newWord);
        } else {
            echo 'Wrong input';
        }
    }

    public function deleteWordFromDb()
    {
        $wordsRepository = new WordsRepository();
        parse_str(file_get_contents("php://input"), $data);
        $word = $data['word'];
        if (!empty($wordsRepository->checkForDuplicates($word))) {
            $id = $wordsRepository->getWordId($word);
            $wordsRepository->deleteWord($id);
        } else {
            echo 'Wrong value';
        }
    }

    protected function addRelationsToDb($word)
    {
        $wordsRepository = new WordsRepository();
        $relationRepository = new RelationsRepository();
        $patternsRepository = new PatternsRepository();

        $patternsFromDb = $patternsRepository->getPatternsFromDb($word);
        $hyphenator = new Hyphenator($patternsFromDb);

        $patt = $hyphenator->getSelectedPatterns($word);

        foreach ($patt as $pattern) {

            $patternId = $patternsRepository->getPatternId($pattern);
            $wordId = $wordsRepository->getWordId($word);
            $relationRepository->addRelationToDb($wordId, $patternId);
        }
    }

    protected function deleteRelationFromDb($word)
    {
        $wordsRepository = new WordsRepository();
        $relationRepository = new RelationsRepository();
        $wordId = $wordsRepository->getWordId($word);
        $relationRepository->deleteRelation($wordId);

    }
}
