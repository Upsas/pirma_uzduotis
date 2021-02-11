<?php

namespace Repositories;

use Database\DatabaseConnection;

class RelationsRepository extends DatabaseConnection
{

    public function addRelationToDb($wordId, $patternId)
    {
        $sql = "INSERT INTO `relations` (`word_id`, `pattern_id`) VALUES (?, ?)";
        $prepares = $this->connect()->prepare($sql);
        $prepares->execute([$wordId, $patternId]);

    }
}
