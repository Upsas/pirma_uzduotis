<?php
declare(strict_types = 1);

namespace Controllers;

use Pattern\Hyphenator;
use Repositories\PatternsRepository;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

class WordsController
{
    /**
     * @return void
     */

    public function getAllHyphenatedWords(): void
    {
        $wordsRepository = new WordsRepository();
        header('Content-Type: application/json');
        echo json_encode($wordsRepository->getAllHyphenatedWordsFromDb());
    }

    /**
     * @return void
     */

    public function insertDataToDb():void
    {
        if (!empty($word = $_POST['word'])) {
            $wordsRepository = new WordsRepository();
            $patternRepisotry = new PatternsRepository();
            $patterns = $patternRepisotry->getPatternsFromDb();
            $hyphenator = new Hyphenator($patterns);
            if (empty($wordsRepository->checkForDuplicates($word))) {
                $wordsRepository->addWords($word, $hyphenator->hyphenate($word));
                $this->addRelationsToDb($word);
            } else {
                echo 'Word already exists';
            }
        } else {
            echo 'Empty data';
        }
    }
    
    /**
     * @return void
     */

    public function editData():void
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
    
    /**
     * @return void
     */

    public function deleteWordFromDb():void
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
    
    /**
     * @param  string $word
     * @return void
     */

    protected function addRelationsToDb(string $word):void
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
    
    /**
     * @param  string $word
     * @return void
     */

    protected function deleteRelationFromDb(string $word):void
    {
        $wordsRepository = new WordsRepository();
        $relationRepository = new RelationsRepository();
        $wordId = $wordsRepository->getWordId($word);
        $relationRepository->deleteRelation($wordId);
    }
}
