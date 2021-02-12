<?php

namespace Repositories;

use Database\DatabaseConnection;
use Pattern\Pattern;
use PDO;

class PatternsRepository extends DatabaseConnection
{

    public function importPatternsToDb($patterns)
    {
        $sql = "INSERT INTO `patterns` ( `pattern`) VALUES ( ?)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `patterns`");
        foreach ($patterns as $pattern) {
            $prepares->execute([$pattern]);
        }
    }

    public function getPatternsFromDb()
    {
        $sql = "SELECT `pattern` FROM `patterns`";
        $patterns = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_CLASS));
        if (!empty($patterns)) {
            foreach ($patterns as $pattern) {
                $patter[] = new Pattern($pattern->pattern);
            }
        }
        return $patter;
    }

    public function getPatternId($pattern)
    {
        $sql = "SELECT `id` FROM `patterns` WHERE `pattern` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $pattern = '%' . $pattern . '%';
        $prepare->execute([$pattern]);
        $id = $prepare->fetch(PDO::FETCH_COLUMN);
        if (!empty($id)) {
            return $id;
        }

    }

}
