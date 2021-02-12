<?php

namespace Repositories;

use Database\DatabaseConnection;
use PDO;

class WordsRepository extends DatabaseConnection
{

    public function checkForDuplicates($word)
    {
        $sql = "SELECT `word` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $word = $prepare->fetch(PDO::FETCH_COLUMN);
        if (!empty($word)) {
            return $word;
        }
    }
    public function getHyphenatedWordFromDb($word)
    {
        $sql = "SELECT `hyphenated_word` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $hyphenatedWord = $prepare->fetch(PDO::FETCH_COLUMN);
        if (!empty($hyphenatedWord)) {
            return $hyphenatedWord;
        }
    }

    public function getAllHyphenatedWordsFromDb()
    {
        $sql = "SELECT `hyphenated_word` FROM `words`";
        $hyphenatedWords = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        return $hyphenatedWords;
    }

    public function deleteWordsFromDb()
    {
        $this->connect()->exec("DELETE  FROM `words`");
    }

    public function addWords($word, $hyphenatedWord)
    {
        if (empty($this->checkForDuplicates($word, $hyphenatedWord))) {
            $sql = "INSERT INTO `words` (`word`, `hyphenated_word`) VALUES (?, ?)";
            $this->connect()->prepare($sql)->execute([$word, $hyphenatedWord]);
        }
    }

    public function getWordId($word)
    {
        $sql = "SELECT `id` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $id = $prepare->fetch(PDO::FETCH_COLUMN);
        if (!empty($id)) {
            return $id;
        }
    }

    public function getAllWordsFromDb()
    {
        $sql = "SELECT `word` FROM `words`";
        $words = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        return $words;
    }
}
