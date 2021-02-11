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
    protected function checkIfFileExists($fileName)
    {
        if (file_exists($fileName)) {
            return file($fileName);
        } else {
            return false;
        }
    }

    public function importPatternsToDb($fileName)
    {
        $file = $this->checkIfFileExists($fileName);
        $sql = "INSERT INTO `patterns` ( `pattern`) VALUES ( ?)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `patterns`");
        foreach ($file as $pattern) {
            $prepares->execute([$pattern]);
        }
    }

    public function getPatternsFromDb($word)
    {
        $sql = "SELECT `pattern` FROM `patterns`";
        $patterns = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        foreach ($patterns as $value) {
            $needle = preg_replace('/[0-9\s.]+/', '', $value);
            $value = trim($value);
            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '$/', $word) && preg_match('/\.$/', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '/', $word) && !preg_match('/\./', $value)) {
                $pattern[] = $value;
            }
        }
        if (!empty($pattern)) {
            return $pattern;
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
