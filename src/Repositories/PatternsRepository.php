<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\DatabaseConnection;
use App\Pattern\Pattern;
use App\Repositories\QueryBuilder;

class PatternsRepository extends DatabaseConnection
{
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }
    
    /**
     * @param  string[]
     * @return void
     */
    
    public function importPatternsToDb(array $patterns): void
    {
        $this->queryBuilder
        ->from('patterns')
        ->deleteAll();
        
        foreach ($patterns as $pattern) {
            $this->queryBuilder
        ->from('patterns')
        ->where(['pattern'])
        ->values('?')
        ->insert([$pattern->getPattern()]);
        }
    }
    
    /**
     * @return Pattern[]
     */
    
    public function getPatternsFromDb(): array
    {
        $patternsFromDb = $this->queryBuilder
        ->from('patterns')
        ->select('pattern')
        ->get();
        foreach ($patternsFromDb as $patterns) {
            $pattern[] = new Pattern($patterns->pattern);
        }
        return $pattern;
    }
    
    /**
     * @param  pattern $pattern
     * @return int $id
     */

    public function getPatternId(pattern $pattern): int
    {
        $id = $this->queryBuilder
        ->select('id')
        ->from('patterns')
        ->where(['pattern'])
        ->like($pattern->getPattern())
        ->getLike();
        return intval($id);
    }

    /**
    * getLimitedDataFromDb
    *
    * @param  int $start
    * @param  int $end
    * @return array
    */
    public function getLimitedDataFromDb(int $start, int $end): array
    {
        $patternsFromDb = $this->queryBuilder
        ->from('patterns')
        ->limitStart($start)
        ->limitEnd($end)
        ->getLimitedData();
        return $patternsFromDb;
    }
    
    /**
     * @param  string $pattern
     * @return void
     */

    public function addPattern(string $pattern): void
    {
        $this->queryBuilder
        ->from('patterns')
        ->where(['pattern'])
        ->values('?')
        ->insert([$pattern]);
    }
    
    /**
     * deletePattern
     *
     * @param  int $id
     */
    public function deletePattern(int $id): void
    {
        $this->queryBuilder
        ->from('patterns')
        ->where(['id', $id])
        ->delete();
    }

    /**
    * getWordDataFromDb
    *
    * @param  int $id
    * @return array
    */
    public function getPatternFromDb(int $id): array
    {
        $patterns = $this->queryBuilder
        ->from('patterns')
        ->where(['id',$id])
        ->all();
        return $patterns;
    }
}
