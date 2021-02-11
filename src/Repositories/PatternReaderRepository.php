<?php

namespace Repositories;

use Database\DatabaseConnection;
use Log\Log;

class PatternReaderRepository extends DatabaseConnection
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
        $sql = "INSERT INTO `patterns` ( `patterns`) VALUES ( ?)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `patterns`");
        foreach ($file as $pattern) {
            $prepares->execute([$pattern]);
        }
    }

    public function getPatternsFromDb($word)
    {
        $sql = "SELECT `patterns` FROM `patterns`";
        $patterns = ($this->connect()->query($sql)->fetchAll());
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

}
