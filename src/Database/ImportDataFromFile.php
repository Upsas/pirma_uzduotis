<?php

namespace Database;

use Database\DatabaseConnection;

class ImportDataFromFile extends DatabaseConnection
{

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
        $sql = "INSERT INTO `patterns` (`id`, `patterns`, `created_date`, `updated_date`) VALUES (NULL, ?, NULL, NULL)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `patterns`");
        foreach ($file as $v) {
            $prepares->execute([$v]);
        }
    }
    public function importWordsToDb($fileName)
    {
        $file = $this->checkIfFileExists($fileName);
        $sql = "INSERT INTO `words` (`id`, `word`, `created_date`, `updated_date`) VALUES (NULL, ?, NULL, NULL)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `words`");
        foreach ($file as $v) {
            $prepares->execute([$v]);
        }
    }
}
