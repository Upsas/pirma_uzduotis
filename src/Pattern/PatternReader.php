<?php

namespace Pattern;

use Log\LoggerInterface;

class PatternReader
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

    public function __construct($word, LoggerInterface $logger)
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

        foreach ($file as $value) {

            $needle = preg_replace('/[0-9]+/', '', $value);
            $needle = trim($needle, '.');
            $needle = trim($needle);
            $value = trim($value);

            if (strpos($value, '.') === 0 && strpos($word, $needle) === 0) {

                $pattern[] = $value;
            } else if (strpos($word, $needle) !== false && strpos($value, '.') !== 0) {

                $pattern[] = $value;
                // strpos($value, '.') > 0 && strpos($word, trim($needle, '.'), 3)
            } else if (strpos(strrev($value), '.') === 0 && strpos(strrev($word), strrev(trim($needle, '.'))) === 0) {
                $pattern[] = $value;
            }
        }
        $pattern = array_values(array_unique($pattern));

        $patternsFromFile['patternsFromFile'] = implode(' ', $pattern);
        $this->logger->info('PatternsFromFile: {patternsFromFile}', $patternsFromFile);

        return $pattern;
    }
}
