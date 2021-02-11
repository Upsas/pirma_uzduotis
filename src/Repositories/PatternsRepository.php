<?php

namespace Repositories;

use Database\DatabaseConnection;
use Log\Log;
use PDO;

class PatternsRepository extends DatabaseConnection
{
    protected $logger;
    public function __construct(Log $logger)
    {
        $this->logger = $logger;
    }

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
        $patterns = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        if (!empty($patterns)) {
            return $patterns;
        }
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
