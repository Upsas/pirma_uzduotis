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

            $needle = preg_replace('/[0-9\s.]+/', '', $value);

            $value = trim($value);
            // if (preg_match('/^\.\w+/', $value)) {
            //     echo $needle;
            // }
            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {
                $test2[] = $value;
                // echo $value . ' ';
            };

            if (strpos($value, '.') === 0 && strpos($word, $needle) === 0) {
                echo $needle . ' ';
                $pattern[] = $value;
                $test1[] = $value;

            } else if (strpos($word, $needle) !== false && strpos($value, '.') !== 0 && !strpos($value, '.')) {

                $pattern[] = $value;

            } else if (strpos(strrev($value), '.') === 0 && strpos(strrev($word), strrev($needle)) === 0) {

                $pattern[] = $value;
            }
        }

        print_r($test1);
        print_r($test2);

        $pattern = array_values(array_unique($pattern));

        $patternsFromFile['patternsFromFile'] = implode(' ', $pattern);
        $this->logger->info('PatternsFromFile: {patternsFromFile}', $patternsFromFile);

        return $pattern;
    }
}
