<?php

namespace Database;

class InsertDataFromProgram extends DatabaseConnection
{
    public function insertSyllableWord($hyphenatedWord)
    {
        $word = $this->word;
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
