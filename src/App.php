<?php

use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;
use Repositories\PatternsRepository;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

class App
{
    public $input;
    public $log;
    public $word;

    public function __construct()
    {
        $this->log = new Log();
        $this->app();
    }
    public function app()
    {
        $this->input();
        $this->source = trim(strtolower(readline('Enter a source (db or file): ')));
        if ($this->source === 'file') {
            $this->filetype = trim(readline('Enter filetype (local or new): '));
        }
        $this->addPatternsToDb();
        $this->output();
        // $this->addWordsFromFileToDb();
        $this->addWordToDb();

    }

    public function input()
    {
        $input = new Input($this->log);
        $this->word = $input->getUserInput();
    }

    public function patternReader()
    {
        $patternReader = new PatternReader($this->word, $this->log);
        if ($this->source === 'db') {
            $PatternsRepository = new PatternsRepository($this->log);
            return ($PatternsRepository->getPatternsFromDb($this->word));
        } else {
            return ($patternReader->getPatternsFromFile());
        }
    }

    public function getPatternPositions()
    {
        $pattern = new Pattern($this->log);
        return $pattern->populatePositionWithNumber($this->word, $this->patternReader());
    }

    public function hyphanteWord()
    {
        $hyphenator = new Hyphenator();
        return $hyphenator->hyphenate($this->word, $this->getPatternPositions());
    }

    public function output()
    {
        $output = new Output($this->hyphanteWord(), $this->log);
        $output->outputResult($this->hyphanteWord());
    }

    public function addPatternsToDb()
    {
        if (!empty($this->filetype) && $this->filetype === 'new') {
            $url = trim(readline('Enter pattern file url: '));
            $PatternsRepository = new PatternsRepository($this->log);
            $PatternsRepository->importPatternsToDb($url);
        }
    }

    public function addWordsFromFileToDb()
    {
        // perdaryti si
        $wordsRepository = new WordsRepository();
        $url = trim(readline('Enter url: '));

        $words = $wordsRepository->checkIfFileExists($url);
        $PatternsRepository = new PatternsRepository($this->log);
        $hyphenator = new Hyphenator();
        $pattern = new Pattern($this->log);

        $wordsRepository->deleteWordsFromDb();
        foreach ($words as $word) {

            $patterns = $PatternsRepository->getPatternsFromDb($word);
            ($p = $pattern->populatePositionWithNumber($word, $patterns));
            $wordsRepository->addWordsFromFileToDb($word, $hyphenator->hyphenate($word, $p));
            $this->addRelationsToDb($word);
        }

        // /opt/lampp/htdocs/praktika/src/Assets/words.txt
    }

    public function addWordToDb()
    {
        $wordsRepository = new WordsRepository();
        if (empty($wordsRepository->checkForDuplicates($this->word))) {
            $wordsRepository->addWordFromCliToDb($this->word, $this->hyphanteWord());
            $this->addRelationsToDb($this->word);
        }

    }
    public function addRelationsToDb($word)
    {
        $wordsRepository = new WordsRepository();
        $dub = $wordsRepository->checkForDuplicates($word);
        $relationRepository = new RelationsRepository();

        $patternReader = new PatternsRepository($this->log);
        $pa = ($patternReader->getPatternsFromDb($dub));
        foreach ($pa as $p) {

            $patternId = $patternReader->getPatternId($p);
            $wordId = $wordsRepository->getWordId($word);
            $relationRepository->addRelationToDb($wordId, $patternId);
        }
    }
}
