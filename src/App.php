<?php

use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\Pattern;
use Pattern\PatternReader;
use Repositories\PatternReaderRepository;

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
        $this->addWordsToDb();
        $this->output();

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
            $patternReaderRepository = new PatternReaderRepository($this->log);
            return ($patternReaderRepository->getPatternsFromDb('mistranslate'));
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
            $url = trim(readline('Enter url: '));
            $patternReaderRepository = new PatternReaderRepository($this->log);
            $patternReaderRepository->importPatternsToDb($url);
        }
    }

    public function addWordsToDb()
    {
        // perdaryti si
        // $inputRepository = new InputRepository();
        // $inputRepository->importWordsToDb('/opt/lampp/htdocs/praktika/src/Assets/words.txt');
        // $url = $inputRepository->importWordsToDb('/opt/lampp/htdocs/praktika/src/Assets/words.txt');

        // $words = $inputRepository->getAllWordsFromDb();
        // $patternReaderRepository = new PatternReaderRepository($this->log);
        // $pa = new Pattern($this->log);
        // $hyphenator = new Hyphenator();

        // for ($i = 0; $i < count($words); $i++) {

        //     $patterns[] = $patternReaderRepository->getPatternsFromDb($words[$i]);
        //     $test[] = $pa->populatePositionWithNumber($words[$i], $patterns[$i]);
        //     $hyphenatedWords[] = $hyphenator->hyphenate($words[$i], $test[$i]);
        // }
        // if (!empty($hyphenatedWords)) {
        //     $inputRepository->importHyphenatedWords('/opt/lampp/htdocs/praktika/src/Assets/words.txt', $hyphenatedWords);}

        // $url = trim(readline('Enter url: '));
        // /opt/lampp/htdocs/praktika/src/Assets/words.txt
    }
}
