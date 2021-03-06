<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Pattern\Hyphenator;
use App\Repositories\PatternsRepository;
use App\Repositories\RelationsRepository;
use App\Repositories\WordsRepository;

class WordsController
{
    private WordsRepository $wordsRepository;
    private PatternsRepository $patternsRepository;
    private Hyphenator $hyphenator;
    private RelationsRepository $relationsRepository;

    public function __construct()
    {
        $this->wordsRepository = new WordsRepository();
        $this->patternsRepository = new PatternsRepository();
        $this->hyphenator = new Hyphenator($this->patternsRepository->getPatternsFromDb());
        $this->relationsRepository = new RelationsRepository();
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
     * insertDataToDb
     *
     * @param  string $word
     * @return void
     */
    public function insertDataToDb($word): void
    {
        if (!empty($word)) {
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
     * editData
     *
     * @param  string $oldWord
     * @param  string $newWord
     * @return void
     */
    public function editData(string $oldWord, string $newWord): void
    {
        $duplicate = $this->wordsRepository->checkForDuplicates($oldWord);
        $newWordDuplicate = $this->wordsRepository->checkForDuplicates($newWord);
        if (isset($duplicate) && !empty($oldWord) && !empty($newWord) && empty($newWordDuplicate)) {
            $newHyphenatedWord = $this->hyphenator->hyphenate($newWord);
            $id = $this->wordsRepository->getWordId($oldWord);
            $this->deleteRelationFromDb($oldWord);
            $this->wordsRepository->updateWord($newWord, $newHyphenatedWord, $id);
            $this->addRelationsToDb($newWord);
        } else {
            echo 'Wrong input';
        }
    }
    
    /**
     * deleteWordFromDb
     *
     * @param  string $word
     * @return void
     */
    public function deleteWordFromDb(string $word): void
    {
        // parse_str(file_get_contents("php://input"), $data);
        // $word = $data['word'];
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

    protected function addRelationsToDb(string $word): void
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

    protected function deleteRelationFromDb(string $word): void
    {
        $wordId = $this->wordsRepository->getWordId($word);
        $this->relationsRepository->deleteRelation($wordId);
    }
}
