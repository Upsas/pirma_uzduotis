<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\WordsRepository;
use App\Controllers\WordsController;

class IndexController
{
    private int $resultsPerPage;
    private WordsRepository $wordsRepository;
    private WordsController $wordsController;
    public function __construct()
    {
        $this->resultsPerPage = 2;
        $this->wordsRepository = new WordsRepository();
        $this->wordsController = new WordsController();
    }
    
    /**
     * getAllData
     *
     * @return Pattern[]
     */
    public function getAllData(): array
    {
        return  $this->wordsRepository->getAllDataFromWordsDb();
    }
    
    /**
     * deleteWord
     *
     * @param  string $word
     * @return void
     */
    public function deleteWord(string $word): void
    {
        $this->wordsController->deleteWordFromDb($word);
        header('location: /praktika/src/Views/');
    }
    
    /**
     * addWord
     *
     * @param  string $word
     * @return void
     */
    public function addWord(string $word): void
    {
        $this->wordsController->insertDataToDb($word);
        header('location: /praktika/src/Views/');
    }
    
    /**
     * updateWord
     *
     * @param  string $oldWord
     * @param  string $newWord
     * @return void
     */
    public function updateWord(string $oldWord, string $newWord): void
    {
        $this->wordsController->editData($oldWord, $newWord);
        header('location: /praktika/src/Views/');
    }
    
    /**
     * searchWord
     *
     * @param  string $word
     * @return Pattern[]
     */
    public function searchWord(string $word): array
    {
        return $this->wordsRepository->getWordDataFromDb($word);
    }
    
    /**
     * numberOfPages
     *
     * @param  int|null $page
     * @return array|null
     */
    public function numberOfPages(?int $page): array
    {
        $numberOfPages = ceil(count($this->getAllData()) / $this->resultsPerPage);
        for ($page = 1; $page <= $numberOfPages; $page++) {
            $pages[] = $page;
        }
        return $pages ?? null;
    }
    
    /**
     * getPaginationData
     *
     * @param  int|null $page
     * @return Pattern[]
     */
    public function getPaginationData(?int $page): array
    {
        if (!isset($page)) {
            $page = 1;
        }
        $thisPageFirstResult = ($page - 1) * $this->resultsPerPage;
        return  $this->wordsRepository->getLimitedDataFromDb($thisPageFirstResult, $this->resultsPerPage);
    }
}
