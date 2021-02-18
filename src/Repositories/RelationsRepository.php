<?php

namespace App\Repositories;

use App\Repositories\QueryBuilder;

class RelationsRepository
{
    private $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * @param  int $wordId
     * @param  int $patternId
     * @return void
     */

    public function addRelationToDb(int $wordId, int $patternId):void
    {
        $this->queryBuilder->from('relations')
        ->where(['word_id', 'pattern_id'])
        ->values('?, ?')
        ->insert([$wordId, $patternId]);
    }
    
    /**
     * @param  int $id
     * @return void
     */

    public function deleteRelation(int $id):void
    {
        $this->queryBuilder
        ->from('relations')
        ->where(['word_id', $id])
        ->delete();
    }
}
