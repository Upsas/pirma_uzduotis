<?php
declare(strict_types = 1);

namespace Controllers;

use Pattern\Hyphenator;
use Repositories\PatternsRepository;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

class WordsController
{
    private object $wordsRepository;
    private object $patternsRepository;
    private object $hyphenator;
    private object $relationsRepository;

    public function __construct()
    {
        $this->instanceAllClasses();
    }
    
    /**
     * @return void
     */

    public function getAllHyphenatedWords(): void
    {
        header('Content-Type: application/json');
        $hyphenatedWords = $this->wordsRepository->getAllHyphenatedWordsFromDb();
        echo json_encode($hyphenatedWords);
    }

    /**
     * @return void
     */

    public function insertDataToDb():void
    {
        if (!empty($word = $_POST['word'])) {
            if (empty($this->wordsRepository->checkForDuplicates($word))) {
                $this->wordsRepository->addWords($word, $this->hyphenator->hyphenate($word));
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
        $duplicate = $this->wordsRepository->checkForDuplicates($word);
        $newWordDuplicate = $this->wordsRepository->checkForDuplicates($newWord);
        if (isset($duplicate) && !empty($word) && !empty($newWord) && empty($newWordDuplicate)) {
            $newHyphenatedWord = $this->hyphenator->hyphenate($newWord);
            $id = $this->wordsRepository->getWordId($word);
            $this->deleteRelationFromDb($word);
            $this->wordsRepository->updateWord($newWord, $newHyphenatedWord, $id);
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
        parse_str(file_get_contents("php://input"), $data);
        $word = $data['word'];
        if (!empty($this->wordsRepository->checkForDuplicates($word))) {
            $id = $this->wordsRepository->getWordId($word);
            $this->wordsRepository->deleteWord($id);
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
        $patterns = $this->hyphenator->getSelectedPatterns($word);

        foreach ($patterns as $pattern) {
            $patternId = $this->patternsRepository->getPatternId($pattern);
            $wordId = $this->wordsRepository->getWordId($word);
            $this->relationsRepository->addRelationToDb($wordId, $patternId);
        }
    }
    
    /**
     * @param  string $word
     * @return void
     */

    protected function deleteRelationFromDb(string $word):void
    {
        $wordId = $this->wordsRepository->getWordId($word);
        $this->relationsRepository->deleteRelation($wordId);
    }
    
    /**
     * @return void
     */
    
    protected function instanceAllClasses():void
    {
        $this->wordsRepository = new WordsRepository();
        $this->patternsRepository = new PatternsRepository();
        $this->hyphenator = new Hyphenator($this->patternsRepository->getPatternsFromDb());
        $this->relationsRepository = new RelationsRepository();
    }
}
