<?php

namespace Pattern;

use Database\DatabaseConnection;
use Log\Log;

class PatternReader extends DatabaseConnection
{
    /**
     * File with all patterns
     *
     * @var url
     */

    protected $file = "./Assets/tex-hyphenation-patterns.txt";

    /**
     * Create a new word instance.
     *
     * @param string $word
     * @return void
     */

    public function __construct($word, Log $logger)
    {
        $this->word = $word;
        $this->logger = $logger;

    }

    /**
     * Checks if file exists
     *
     * @param $this->file
     * @return array $this->file
     */

    protected function getDataFromFile()
    {
        if (file_exists($this->file)) {
            return file($this->file);
        } else {
            return false;
        }
    }

    /**
     * Get specific patterns using word
     *
     * @param string $word
     * @param array $file
     * @return array $pattern
     */

    public function getPatterns()
    {
        $word = $this->word;
        $file = $this->getDataFromFile();
        // if (count(explode(' ', $word)) > 1) {
        //     $array = (explode(' ', $word));
        //     $array = array_filter($array);
        //     foreach ($array as $a) {

        //         foreach ($file as $v) {
        //             $needle = preg_replace('/[0-9\s.]+/', '', $v);
        //             $v = trim($v);

        //             if (preg_match('/^' . $needle . '/', $a) && preg_match('/^\./', $v)) {

        //                 $t[$a][] = $v;

        //             } else if (preg_match('/' . $needle . '$/', $a) && preg_match('/\.$/', $v)) {

        //                 $t[$a][] = $v;

        //             } else if (preg_match('/' . $needle . '/', $a) && !preg_match('/\./', $v)) {

        //                 $t[$a][] = $v;

        //             }
        //         }}} else {
        foreach ($file as $value) {

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
        // }
        // if (!empty($t)) {
        //     return ($t);
        // }
        if (!empty($pattern)) {

            $pattern = array_values(array_unique($pattern));
            $patternsFromFile['patternsFromFile'] = implode(' ', $pattern);
            $this->logger->info('PatternsFromFile: {patternsFromFile}', $patternsFromFile);
            return $pattern;
        }
    }
    public function getPatternsFromDb()
    {
        $word = $this->word;

        $sql = "SELECT `patterns` FROM `patterns`";
        $db = ($this->connect()->query($sql)->fetchAll());
        foreach ($db as $value) {
            $needle = preg_replace('/[0-9\s.]+/', '', $value['patterns']);
            $value = trim($value['patterns']);
            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {

                $pattern[] = $value;

            } else if (preg_match('/' . $needle . '$/', $word) && preg_match('/\.$/', $value)) {

                $pattern[] = $value;

            } else if (preg_match('/' . $needle . '/', $word) && !preg_match('/\./', $value)) {

                $pattern[] = $value;

            }
        }
        return ($pattern);
    }

    public function insertSyllableWord($hyphenatedWord)
    {
        $word = $this->word;
        $sql = "INSERT INTO `syllable_words` ( `syllable_word`,`hyphenated_word`, `created_date`, `updated_date`) VALUES ( ?, ?, NULL, NULL)";
        $prepare = $this->connect()->prepare($sql);

        $prepare->execute([$word, $hyphenatedWord]);

        $stmt = "SELECT MAX(id) FROM `syllable_words`";
        $this->id = $this->connect()->query($stmt)->fetch();

    }
    public function insertCorrectPatterns()
    {
        $id = (intval($this->id['MAX(id)']));
        $string = (implode(' ', $this->getPatternsFromDb()));
        $sql = "INSERT INTO `correct_patterns` ( `correct_pattern`, `sylable_word_id`,  `created_date`, `updated_date`) VALUES (?, ?, NULL,  NULL)";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$string, $id]);
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
    public function outputPatternsFromDb($word)
    {
        $sql = "SELECT `id` FROM `syllable_words` WHERE `syllable_word` = ?";
        $prepare = $this->connect()->prepare($sql);
        $prepare->execute([$word]);
        $values = $prepare->fetch();
        if (!empty($values)) {
            if (count($values) > 0) {
                $id = $values['id'];
            }
        }
        $patterns = "SELECT `correct_pattern` FROM `correct_patterns` WHERE `sylable_word_id` = $id";
        $t = $this->connect()->query($patterns);
        $correctPatterns = $t->fetch();
        if (!empty($correctPatterns)) {
            return $correctPatterns['correct_pattern'];
        }

    }
}
