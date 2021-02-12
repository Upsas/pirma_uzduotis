<?php

use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Repositories\PatternsRepository;
use Repositories\WordsRepository;

class Api
{
    public $log;

    public function __construct()
    {
        $this->log = new Log();
        $this->api();
    }
    public function api()
    {

        $this->getAllHyphenatedWords();
        $this->insertDataToDb();
        $this->editData();
    }

    public function getAllHyphenatedWords()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/praktika/src/') {
            $wordsRepository = new WordsRepository();
            echo json_encode($wordsRepository->getAllWordsFromDb());
        }
    }

    public function insertDataToDb()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/praktika/src/') {

            $wordsRepository = new WordsRepository();
            $word = $_POST['word'];
            if (empty($wordsRepository->checkForDuplicates($word))) {

                $wordsRepository->addWords($word, $this->hyphenateWord($word));
            }
        }
    }

    public function hyphenateWord($word)
    {

        $pattern = new Pattern($this->log);

        $numbersArray = $pattern->populatePositionWithNumber($word, $this->getSelectedPattern($word));
        $hyphenator = new Hyphenator();
        return $hyphenator->hyphenate($word, $numbersArray);
    }

    public function getSelectedPattern($word)
    {
        // pertvarkyti veliau

        $patternsRepository = new PatternsRepository($this->log);
        $patternsFromDb = $patternsRepository->getPatternsFromDb();

        foreach ($patternsFromDb as $value) {

            $needle = preg_replace('/[0-9\s.]+/', '', $value);
            $value = trim($value);

            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {
                $patterns[] = $value;
            } else if (preg_match('/' . $needle . '$/', $word) && preg_match('/\.$/', $value)) {
                $patterns[] = $value;
            } else if (preg_match('/' . $needle . '/', $word) && !preg_match('/\./', $value)) {
                $patterns[] = $value;
            }
        }
        return $patterns;
    }

    public function editData()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' && $_SERVER['REQUEST_URI'] === '/praktika/src/') {
            parse_str(file_get_contents("php://input"), $data);
            $word = $data['word'];
            $newWord = $data['newWord'];

            $wordsRepository = new WordsRepository();
            $duplicate = $wordsRepository->checkForDuplicates($word);
            if ($duplicate) {
                echo $newHyphenatedWord = $this->hyphenateWord($newWord);
                echo $id = $wordsRepository->getWordId($word);
                $wordsRepository->updateWord($newWord, $newHyphenatedWord, $id);

            }
        }

    }
}
