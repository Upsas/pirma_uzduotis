<?php

namespace Repositories;

use Database\DatabaseConnection;

class InputRepository extends DatabaseConnection
{
    protected function checkIfFileExists($fileName)
    {
        if (file_exists($fileName)) {
            return file($fileName);
        } else {
            return false;
        }
    }

    public function importWordsToDb($fileName)
    {
        $file = $this->checkIfFileExists($fileName);
        $sql = "INSERT INTO `words` (`word`, `hyphenated_word`) VALUES (?, '?')";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `words`");
        $this->connect()->exec("DELETE  FROM `relations`");
        foreach ($file as $word) {
            $prepares->execute([$word]);
        }
    }

    public function importHyphenatedWords($fileName, $hyphenatedWords)
    {
        $file = $this->checkIfFileExists($fileName);
        $sql = "INSERT INTO `words` (`word`, `hyphenated_word`) VALUES (?, ?)";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `words`");
        $this->connect()->exec("DELETE  FROM `relations`");

        for ($i = 0; $i < count($file); $i++) {
            $prepares->execute([$file[$i], $hyphenatedWords[$i]]);
        }
    }
    public function checkForDuplicates($word)
    {
        $sql = "SELECT `word`, `hyphenated_word` FROM `words` WHERE `word` = ?";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$word]);
        $word = $prepare->fetch();
        if (!empty($word)) {
            if (count($word) > 0) {
                return $word['hyphenated_word'];
            }
        }
    }

    public function getAllWordsFromDb()
    {
        $sql = "SELECT `word` FROM `words`";
        $words = ($this->connect()->query($sql)->fetchAll());
        return $words;
    }
}
