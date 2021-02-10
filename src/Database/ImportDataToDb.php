<?php

namespace Database;

use Database\DatabaseConnection;
use Database\Repository;

class ImportDataToDb extends DatabaseConnection
{
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
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
        foreach ($file as $v) {
            $prepares->execute([$v]);
        }
    }
    public function importWordsToDb($fileName)
    {
        $file = $this->checkIfFileExists($fileName);
        $sql = "INSERT INTO `syllable_words` (`syllable_word`, `hyphenated_word` ) VALUES (?, 'test')";
        $prepares = $this->connect()->prepare($sql);
        $this->connect()->exec("DELETE  FROM `syllable_words`");
        foreach ($file as $v) {
            $prepares->execute([$v]);
        }
    }

    public function insertSyllableWord($word, $hyphenatedWord)
    {
        $sql = "INSERT INTO `syllable_words` ( `syllable_word`,`hyphenated_word`) VALUES ( ?, ?)";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$word, $hyphenatedWord]);

        $stmt = "SELECT MAX(id) FROM `syllable_words`";
        $this->id = $this->connect()->query($stmt)->fetch();

    }

    public function insertCorrectPatterns($patterns)
    {
        $id = (intval($this->id['MAX(id)']));
        $string = (implode(' ', $patterns));
        $sql = "INSERT INTO `correct_patterns` ( `correct_pattern`, `sylable_word_id`) VALUES (?, ?)";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$string, $id]);
    }
}
