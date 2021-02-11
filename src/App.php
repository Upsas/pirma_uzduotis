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
            $this->addPatternsToDb();

        }
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
        $patternReader = new PatternReader($this->log);
        if ($this->source === 'db') {
            $PatternsRepository = new PatternsRepository($this->log);
            $patternsFromDb = $PatternsRepository->getPatternsFromDb($this->word);
            return $patternReader->getSelectedPatterns($this->word, $patternsFromDb);
        } else {
            return ($patternReader->getSelectedPatterns($this->word, ''));
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
            $patterns = file($url);
            $PatternsRepository = new PatternsRepository($this->log);
            $wordsRepository = new WordsRepository();
            $wordsRepository->deleteWordsFromDb();
            $PatternsRepository->importPatternsToDb($patterns);
        }
        // /opt/lampp/htdocs/praktika/src/Assets/tex-hyphenation-patterns.txt
    }

    public function addWordsFromFileToDb()
    {
        $wordsRepository = new WordsRepository();
        $url = trim(readline('Enter url: '));

        $words = file($url);
        $patternsRepository = new PatternsRepository($this->log);
        $hyphenator = new Hyphenator();
        $pattern = new Pattern($this->log);
        $patternReader = new PatternReader($this->log);
        $wordsRepository->deleteWordsFromDb();
        foreach ($words as $word) {

            $patternsFromDb = $patternsRepository->getPatternsFromDb($word);
            $patterns = $patternReader->getSelectedPatterns($word, $patternsFromDb);

            $p = $pattern->populatePositionWithNumber($word, $patterns);
            $wordsRepository->addWords($word, $hyphenator->hyphenate($word, $p));
            $this->addRelationsToDb($word);
        }

        // /opt/lampp/htdocs/praktika/src/Assets/words.txt
    }

    public function addWordToDb()
    {
        $wordsRepository = new WordsRepository();
        if (empty($wordsRepository->checkForDuplicates($this->word))) {
            $wordsRepository->addWords($this->word, $this->hyphanteWord());
            $this->addRelationsToDb($this->word);
        }

    }
    public function addRelationsToDb($word)
    {
        $wordsRepository = new WordsRepository();
        $relationRepository = new RelationsRepository();

        $patternsRepository = new PatternsRepository($this->log);
        $patternsFromDb = $patternsRepository->getPatternsFromDb($word);
        $patternReader = new PatternReader($this->log);
        $patterns = $patternReader->getSelectedPatterns($word, $patternsFromDb);

        foreach ($patterns as $pattern) {

            $patternId = $patternsRepository->getPatternId($pattern);
            $wordId = $wordsRepository->getWordId($word);
            $relationRepository->addRelationToDb($wordId, $patternId);
        }
    }
}
