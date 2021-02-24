<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\PatternsRepository;

class PatternController
{
    private PatternsRepository $patternsRepository;

    public function __construct()
    {
        $this->resultsPerPage = 8;
        $this->patternsRepository = new PatternsRepository();
    }
    /**
     * getAllData
     *
     * @return Pattern[]
     */
    public function getAllData(): array
    {
        return  $this->patternsRepository->getPatternsFromDb();
    }
 
    /**
     * addPatternToDb
     *
     * @param  string $pattern
     * @return void
     */
    public function addPatternToDb(string $pattern): void
    {
        $this->patternsRepository->addPattern($pattern);
        header('location: /praktika/src/Views/patterns.php');
    }
    /**
     * deletePatternFromDb
     *
     * @param  string $id
     * @return void
     */
    public function deletePatternFromDb(string $id): void
    {
        $this->patternsRepository->deletePattern(intval($id));
        header('location: /praktika/src/Views/patterns.php');
    }
    
    /**
     * searchPattern
     *
     * @param  int $id
     * @return array
     */
    public function searchPattern(int $id): array
    {
        return $this->patternsRepository->getPatternFromDb($id);
    }

    /**
      * numberOfPages
      *
      * @param  int|null $page
      * @return array|null
      */
    public function numberOfPages(?int $page): array
    {
        if ($page == 0) {
            $page = 1;
        }
        $numberOfPages = ceil(count($this->getAllData()) / $this->resultsPerPage);
        for ($page = 1; $page <= $numberOfPages; $page++) {
            $pages[] = $page;
        }
        return array_slice($pages, -10, 10);
    }
    
    // /**
    //  * getPaginationData
    //  *
    //  * @param  int|null $page
    //  * @return object[]
    //  */
    public function getPaginationData(?int $page): array
    {
        if (!isset($page)) {
            $page = 1;
        }
        $thisPageFirstResult = ($page - 1) * $this->resultsPerPage;
        return  $this->patternsRepository->getLimitedDataFromDb($thisPageFirstResult, $this->resultsPerPage);
    }
}
