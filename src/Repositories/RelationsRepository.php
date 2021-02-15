<?php

namespace Repositories;

use Database\DatabaseConnection;

class RelationsRepository extends DatabaseConnection
{
    
    /**
     * @param  int $wordId
     * @param  int $patternId
     * @return void
     */

    public function addRelationToDb(int $wordId, int $patternId):void
    {
        $sql = "INSERT INTO `relations` (`word_id`, `pattern_id`) VALUES (?, ?)";
        $prepares = $this->connect()->prepare($sql);
        $prepares->execute([$wordId, $patternId]);
    }
    
    /**
     * @param  int $id
     * @return void
     */

    public function deleteRelation(int $id):void
    {
        $sql = "DELETE FROM `relations` WHERE `word_id` = ?";
        $this->connect()->prepare($sql)->execute([$id]);
    }
}
