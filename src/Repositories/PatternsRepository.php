<?php
declare(strict_types = 1);

namespace Repositories;

use Database\DatabaseConnection;
use Pattern\Pattern;
use Repositories\QueryBuilder;
use PDO;

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
    
    public function importPatternsToDb(array $patterns):void
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
    
    public function getPatternsFromDb():array
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
     * @param  string $pattern
     * @return int $id
     */

    public function getPatternId(string $pattern)
    {
        $id = $this->queryBuilder
        ->select('id')
        ->from('patterns')
        ->where(['pattern'])
        ->like($pattern)
        ->getLike();
        
        return ($id);
    }
}
