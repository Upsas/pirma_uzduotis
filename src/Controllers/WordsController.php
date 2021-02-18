<?php
declare(strict_types = 1);

namespace App\Controllers;

use App\Pattern\Hyphenator;
use App\Factories\RepositoryFactory;

class WordsController
{
    private object $repositoriesFactory;
    private object $hyphenator;

    public function __construct()
    {
        $this->repositoriesFactory = new RepositoryFactory();
        $this->hyphenator = new Hyphenator($this->repositoriesFactory->createRepository('Patterns')->getPatternsFromDb());
    }
    
    /**
     * @return void
     */

    public function getAllHyphenatedWords(): void
    {
        header('Content-Type: application/json');
        $hyphenatedWords = $this->repositoriesFactory->createRepository('Words')->getAllHyphenatedWordsFromDb();
        echo json_encode($hyphenatedWords);
    }

    /**
     * @return void
     */

    public function insertDataToDb():void
    {
        if (!empty($word = $_POST['word'])) {
            if (empty($this->repositoriesFactory->createRepository('Words')->checkForDuplicates($word))) {
                $this->repositoriesFactory->createRepository('Words')->addWords($word, $this->hyphenator->hyphenate($word));
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
        $duplicate = $this->repositoriesFactory->createRepository('Words')->checkForDuplicates($word);
        $newWordDuplicate = $this->repositoriesFactory->createRepository('Words')->checkForDuplicates($newWord);
        if (isset($duplicate) && !empty($word) && !empty($newWord) && empty($newWordDuplicate)) {
            $newHyphenatedWord = $this->hyphenator->hyphenate($newWord);
            $id = $this->repositoriesFactory->createRepository('Words')->getWordId($word);
            $this->deleteRelationFromDb($word);
            $this->repositoriesFactory->createRepository('Words')->updateWord($newWord, $newHyphenatedWord, $id);
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
        if (!empty($this->repositoriesFactory->createRepository('Words')->checkForDuplicates($word))) {
            $id = $this->repositoriesFactory->createRepository('Words')->getWordId($word);
            $this->repositoriesFactory->createRepository('Words')->deleteWord($id);
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
        ($patterns = $this->hyphenator->getSelectedPatterns($word));

        foreach ($patterns as $pattern) {
            ($patternId = $this->repositoriesFactory->createRepository('Patterns')->getPatternId($pattern));
            
            $wordId = $this->repositoriesFactory->createRepository('Words')->getWordId($word);
            $this->repositoriesFactory->createRepository('Relations')->addRelationToDb($wordId, $patternId);
        }
    }
    
    /**
     * @param  string $word
     * @return void
     */

    protected function deleteRelationFromDb(string $word):void
    {
        $wordId = $this->repositoriesFactory->createRepository('Words')->getWordId($word);
        $this->repositoriesFactory->createRepository('Relations')->deleteRelation($wordId);
    }
}
