<?php

namespace Database;

use Database\DatabaseConnection;

class Repository extends DatabaseConnection
{
    public function getPatternsFromDb()
    {
        $sql = "SELECT `patterns` FROM `patterns`";
        $values = ($this->connect()->query($sql)->fetchAll());
        return $values;
    }

    public function checkForDublicates($word)
    {
        $sql = "SELECT `syllable_word`, `hyphenated_word` FROM `syllable_words` WHERE `syllable_word` = ?";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$word]);
        $values = $prepare->fetch();
        if (!empty($values)) {
            if (count($values) > 0) {
                return $values['hyphenated_word'];
            }
        }
    }

}
