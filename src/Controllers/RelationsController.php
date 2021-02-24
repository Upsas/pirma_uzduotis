<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\WordsRepository;
use App\Controllers\WordsController;
use App\Repositories\PatternsRepository;
use App\Repositories\RelationsRepository;

class RelationsController
{
    private WordsRepository $wordsRepository;
    private RelationsRepository $relationsRepository;
    private PatternsRepository $patternsRepository;

    public function __construct()
    {
        $this->resultsPerPage = 8;
        $this->wordsRepository = new WordsRepository();
        $this->relationsRepository = new RelationsRepository();
        $this->patternsRepository = new PatternsRepository();
    }
    
    /**
     * getAllData
     *
     * @return Pattern[]
     */
    public function getRelations(): array
    {
        return  $this->relationsRepository->getRelations();
    }

    public function getWordsById($id)
    {
        return $this->wordsRepository->getWordsById($id);
    }
    public function numberOfPages(?int $page): array
    {
        if ($page == 0) {
            $page = 1;
        }
        $numberOfPages = ceil(count($this->getRelations()) / $this->resultsPerPage);
        for ($page = 1; $page <= $numberOfPages; $page++) {
            $pages[] = $page;
        }
        return $pages;
    }
    
    /**
     * getPaginationData
     *
     * @param  int|null $page
     * @return object[]
     */
    public function getPaginationData(?int $page): array
    {
        if (!isset($page)) {
            $page = 1;
        }
        $thisPageFirstResult = ($page - 1) * $this->resultsPerPage;
        return  $this->relationsRepository->getLimitedDataFromDb($thisPageFirstResult, $this->resultsPerPage);
    }

    public function getPatternsById($id)
    {
        return $this->patternsRepository->getPatternFromDb($id);
    }
    public function searchRelation(string $word): array
    {
        $id = $this->wordsRepository->getWordId($word);
        return $this->relationsRepository->getAllRelationsByWordId($id);
    }
}
