<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\DatabaseConnection;
use App\Pattern\Pattern;
use App\Repositories\QueryBuilder;

class PatternsRepository extends DatabaseConnection
{
    private $queryBuilder;

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
        $patterns = $this->queryBuilder
        ->from('patterns')
        ->select('pattern')
        ->get();
        foreach ($patterns as $pattern) {
            $patter[] = new Pattern($pattern->pattern);
        }
        return $patter;
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
}
