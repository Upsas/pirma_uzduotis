<?php

namespace Repositories;

use Database\DatabaseConnection;
use PDO;

class WordsRepository extends DatabaseConnection
{
    
    /**
     * @param  string $word
     * @return string $word
     */

    public function checkForDuplicates(string $word): string
    {
        $sql = "SELECT `word` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $word = $prepare->fetch(PDO::FETCH_COLUMN);
        return $word;
    }
    
    /**
     * @param  string $word
     * @return string $hyphenatedWord
     */
    
    public function getHyphenatedWordFromDb(string $word): string
    {
        $sql = "SELECT `hyphenated_word` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $hyphenatedWord = $prepare->fetch(PDO::FETCH_COLUMN);
        return $hyphenatedWord;
    }
    
    /**
     * @return string[]
     */

    public function getAllHyphenatedWordsFromDb(): array
    {
        $sql = "SELECT `hyphenated_word` FROM `words`";
        $hyphenatedWords = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        return $hyphenatedWords;
    }
    
    /**
     * @return void
     */

    public function deleteWordsFromDb():void
    {
        $this->connect()->exec("DELETE  FROM `words`");
    }
    
    /**
     * @param  int $id
     * @return void
     */

    public function deleteWord(int $id): void
    {
        $sql = "DELETE FROM `words` WHERE `words`.`id` = ?";
        $this->connect()->prepare($sql)->execute([$id]);
    }
        
    /**
     * @param  string $word
     * @param  string $hyphenatedWord
     * @return void
     */

    public function addWords(string $word, string $hyphenatedWord): void
    {
        $sql = "INSERT INTO `words` (`word`, `hyphenated_word`) VALUES (?, ?)";
        $this->connect()->prepare($sql)->execute([$word, $hyphenatedWord]);
    }
    
    /**
     * @param  string $word
     * @return int $id
     */

    public function getWordId(string $word): int
    {
        $sql = "SELECT `id` FROM `words` WHERE `word` LIKE ?";
        $prepare = $this->connect()->prepare($sql);
        $word = '%' . $word . '%';
        $prepare->execute([$word]);
        $id = $prepare->fetch(PDO::FETCH_COLUMN);
        return $id;
    }
    
    /**
     * @return string[]
     */
    
    public function getAllWordsFromDb(): array
    {
        $sql = "SELECT `word` FROM `words`";
        $words = ($this->connect()->query($sql)->fetchAll(PDO::FETCH_COLUMN));
        return $words;
    }
    
    /**
     *
     * @param  string $newWord
     * @param  string $newHyphenatedWord
     * @param  int $id
     * @return void
     */

    public function updateWord(string $newWord, string $newHyphenatedWord, int $id): void
    {
        $sql = "UPDATE `words` SET `word` = ?, `hyphenated_word` = ? WHERE `words`.`id` = ?";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$newWord, $newHyphenatedWord, $id]);
    }
}
