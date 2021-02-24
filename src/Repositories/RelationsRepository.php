<?php

namespace App\Repositories;

use App\Repositories\QueryBuilder;
use App\Database\DatabaseConnection;

class RelationsRepository extends DatabaseConnection
{
    private QueryBuilder $queryBuilder;

    public function __construct()
    {
        $this->queryBuilder = new QueryBuilder();
    }

    /**
     * @param  int $wordId
     * @param  int $patternId
     * @return void
     */

    public function addRelationToDb(int $wordId, int $patternId): void
    {
        $this->queryBuilder
        ->from('relations')
        ->where(['word_id', 'pattern_id'])
        ->values('?, ?')
        ->insert([$wordId, $patternId]);
    }
    
    /**
     * @param  int $id
     * @return void
     */

    public function deleteRelation(int $id): void
    {
        $this->queryBuilder
        ->from('relations')
        ->where(['word_id', $id])
        ->delete();
    }
    
    /**
     * getRelations
     *
     * @return array
     */
    public function getRelations(): array
    {
        return $this->queryBuilder
        ->from('relations')
        ->all();
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
        $relations = $this->queryBuilder
        ->from('relations')
        ->limitStart($start)
        ->limitEnd($end)
        ->getLimitedData();
        return $relations;
    }

    public function getAllRelationsByWordId(int $id): array
    {
        return $this->queryBuilder
        ->from('relations')
        ->where(['word_id', $id])
        ->all();
    }
}
