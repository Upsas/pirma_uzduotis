<?php

declare(strict_types=1);

namespace App;

use App\Api;
use App\Factories\PatternFactory;
use App\Log\Log;
use App\InputOutput\Input;
use App\InputOutput\Output;
use App\Pattern\PatternReader;
use App\Repositories\WordsRepository;
use App\Repositories\PatternsRepository;
use App\Repositories\RelationsRepository;

class App
{
    private object $wordsRepository;
    private object $patternsRepository;
    private object $patternFactory;
    private object $relationsRepository;
    private object $patternReader;
    public function __construct(Router $route)
    {
        $this->router = $route;
        $this->log = new Log();
        $this->patternFactory = new PatternFactory();
        $this->instancesOfClasses();
        $this->app();
    }
    
    /**
     * @return void
     */
    
    public function app(): void
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

            $this->displayPatterns();
            $this->addWordToDb();

        // $this->addWordsFromFileToDb();
        } else {
            $api = new Api($this->router);
        }
    }
    
    /**
     * @return void
     */

    public function input(): void
    {
        $this->input = new Input($this->log);
        $this->word = $this->input->getUserInput();
    }
    
    /**
     * @return Pattern[]
     */
    
    public function patternReader(): array
    {
        if ($this->source === 'db') {
            return $this->patternsRepository->getPatternsFromDb();
        } else {
            return $this->patternReader->getPatterns('local');
        }
    }
    
    /**
     * @return string
     */
    
    public function hyphanteWord(): string
    {
        $hyphenator = $this->patternFactory->createHyphenatorClass($this->patternReader());
        return $hyphenator->hyphenate($this->word);
    }
    
    /**
     * @return void
     */

    public function output(): void
    {
        $output = new Output($this->log);
        $output->outputResult($this->hyphanteWord());
    }
    
    /**
     * @return null|string
     */

    public function getHyphenatedWord(): ?string
    {
        if (!empty($this->wordsRepository->checkForDuplicates($this->word))) {
            return $this->wordsRepository->getHyphenatedWordFromDb($this->word);
        }
        return null;
    }
    
    /**

     * @return void
     */
    public function displayPatterns(): void
    {
        if ($this->source === 'db') {
            $patterns = $this->patternsRepository->getPatternsFromDb();
            $hyphenator = $this->patternFactory->createHyphenatorClass($patterns);
            $patt = $hyphenator->getSelectedPatterns($this->word);
            echo implode(' ', $patt) . PHP_EOL;
        }
    }
    
    /**
     * @return void
     */

    protected function addPatternsToDb(): void
    {
        if (!empty($this->filetype) && $this->filetype === 'new') {
            $url = trim(readline('Enter pattern file url: '));
            $patterns = $this->patternReader->getPatterns($url);
            $this->wordsRepository->deleteWordsFromDb();
            $this->patternsRepository->importPatternsToDb($patterns);
        }
    }
    
    /**
     * @return void
     */

    protected function addWordsFromFileToDb(): void
    {
        $url = trim(readline('Enter url: '));
        $words = file($url);

        $patterns = $this->patternReader();
        $hyphenator = $this->patternFactory->createHyphenatorClass($patterns);

        $this->wordsRepository->deleteWordsFromDb();
        foreach ($words as $word) {
            $word = trim($word);
            $this->wordsRepository->addWords($word, $hyphenator->hyphenate($word));
            $this->addRelationsToDb($word);
        }
    }
    
    /**
     * @return void
     */

    public function addWordToDb(): void
    {
        if (empty($this->wordsRepository->checkForDuplicates($this->word))) {
            $this->wordsRepository->addWords($this->word, $this->hyphanteWord());
            $this->addRelationsToDb($this->word);
        }
    }
    
    /**
     * @param  string $word
     * @return void
     */

    protected function addRelationsToDb(string $word): void
    {
        $patterns = $this->patternReader();
        $hyphenator = $this->patternFactory->createHyphenatorClass($patterns);
        $patterns = $hyphenator->getSelectedPatterns($word);

        foreach ($patterns as $pattern) {
            ($patternId = $this->patternsRepository->getPatternId($pattern));
            ($wordId = $this->wordsRepository->getWordId($word));
            $this->relationsRepository->addRelationToDb($wordId, $patternId);
        }
    }
    
    /**
     * @return void
     */

    protected function instancesOfClasses(): void
    {
        $this->wordsRepository = new WordsRepository();
        $this->patternsRepository = new PatternsRepository();
        $this->relationsRepository = new RelationsRepository();
        $this->patternReader = new PatternReader();
    }
}
