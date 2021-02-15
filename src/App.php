<?php
declare(strict_types = 1);

use InputOutput\Input;
use InputOutput\Output;
use Log\Log;
use Pattern\Hyphenator;
use Pattern\PatternReader;
use Repositories\PatternsRepository;
use Repositories\RelationsRepository;
use Repositories\WordsRepository;

class App
{
    public function __construct()
    {
        $this->log = new Log();
        $this->app();
    }
    
    /**
     * @return void
     */
    
    public function app():void
    {
        if (php_sapi_name() === 'cli') {
            $this->input();
            $this->source = trim(strtolower(readline('Enter a source (db or file): ')));
            $this->patternReader();

            if ($this->source === 'file') {
                $this->filetype = trim(readline('Enter filetype (local or new): '));
                $this->addPatternsToDb();
            }
            if (!empty($this->getHyphenatedWord())) {
                echo $this->getHyphenatedWord() . PHP_EOL;
            } else {
                $this->output();
            }

            $this->getPatterns();
            $this->addWordToDb();

        // $this->addWordsFromFileToDb();
        } else {
            $api = new Api();
        }
    }
    
    /**
     * @return void
     */

    public function input():void
    {
        $input = new Input($this->log);
        $this->word = $input->getUserInput();
    }
    
    /**
     * @return Pattern[]
     */
    
    public function patternReader(): array
    {
        $patternReader = new PatternReader();
        if ($this->source === 'db') {
            $PatternsRepository = new PatternsRepository();
            return $PatternsRepository->getPatternsFromDb();
        } else {
            return $patternReader->getPatterns('local');
        }
    }
    
    /**
     * @return string
     */
    
    public function hyphanteWord():string
    {
        $hyphenator = new Hyphenator($this->patternReader());
        return $hyphenator->hyphenate($this->word);
    }
    
    /**
     * @return void
     */

    public function output(): void
    {
        $output = new Output($this->hyphanteWord(), $this->log);
        $output->outputResult($this->hyphanteWord());
    }
    
    /**
     * @return string
     */

    public function getHyphenatedWord():string
    {
        $wordsRepository = new WordsRepository();
        if (!empty($wordsRepository->checkForDuplicates($this->word)) && $this->source === 'db' || $this->source === 'file') {
            return $wordsRepository->getHyphenatedWordFromDb($this->word);
        }
    }

    public function getPatterns(): void
    {
        if ($this->source === 'db') {
            $patternsRepository = new PatternsRepository($this->log);
            $patternsFromDb = $patternsRepository->getPatternsFromDb($this->word);
            $hyphenator = new Hyphenator($patternsFromDb);
            $patt = $hyphenator->getSelectedPatterns($this->word);
            echo implode(' ', $patt) . PHP_EOL;
        }
    }
    
    /**
     * @return void
     */

    public function addPatternsToDb():void
    {
        if (!empty($this->filetype) && $this->filetype === 'new') {
            $url = trim(readline('Enter pattern file url: '));
            $patternsReader = new PatternReader();
            $patterns = $patternsReader->getPatterns($url);

            $PatternsRepository = new PatternsRepository($this->log);
            $wordsRepository = new WordsRepository();
            $wordsRepository->deleteWordsFromDb();
            $PatternsRepository->importPatternsToDb($patterns);
        }
        // /opt/lampp/htdocs/praktika/src/Assets/tex-hyphenation-patterns.txt
    }
    
    /**
     *
     * @return void
     */

    public function addWordsFromFileToDb():void
    {
        $wordsRepository = new WordsRepository();

        $url = trim(readline('Enter url: '));
        $words = file($url);

        $patterns = $this->patternReader();
        $hyphenator = new Hyphenator($patterns);

        $wordsRepository->deleteWordsFromDb();
        foreach ($words as $word) {
            $word = trim($word);
            $wordsRepository->addWords($word, $hyphenator->hyphenate($word));
            $this->addRelationsToDb($word);
        }
        // /opt/lampp/htdocs/praktika/src/Assets/words.txt
    }
    
    /**
     * @return void
     */

    public function addWordToDb():void
    {
        $wordsRepository = new WordsRepository();
        if (empty($wordsRepository->checkForDuplicates($this->word))) {
            $wordsRepository->addWords($this->word, $this->hyphanteWord());
            $this->addRelationsToDb($this->word);
        }
    }
    
    /**
     * @param  string $word
     * @return void
     */

    public function addRelationsToDb(string $word):void
    {
        $wordsRepository = new WordsRepository();
        $relationRepository = new RelationsRepository();
        $patternsRepository = new PatternsRepository();

        $patternsFromDb = $patternsRepository->getPatternsFromDb($word);
        $hyphenator = new Hyphenator($patternsFromDb);

        $patt = $hyphenator->getSelectedPatterns($word);

        foreach ($patt as $pattern) {
            $patternId = $patternsRepository->getPatternId($pattern);
            $wordId = $wordsRepository->getWordId($word);
            $relationRepository->addRelationToDb($wordId, $patternId);
        }
    }
}
