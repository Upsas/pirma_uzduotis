<?php

namespace Pattern;

use Log\Log;

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

    protected function checkIfFileExists()
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

    public function getPatternsFromFile()
    {
        $file = $this->checkIfFileExists();
        foreach ($file as $value) {

            $needle = preg_replace('/[0-9\s.]+/', '', $value);
            $value = trim($value);

            if (preg_match('/^' . $needle . '/', $this->word) && preg_match('/^\./', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '$/', $this->word) && preg_match('/\.$/', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '/', $this->word) && !preg_match('/\./', $value)) {
                $pattern[] = $value;
            }
        }

        if (!empty($pattern)) {

            $pattern = array_values(array_unique($pattern));
            $patternsFromFile['patternsFromFile'] = implode(' ', $pattern);
            $this->logger->info('PatternsFromFile: {patternsFromFile}', $patternsFromFile);

            return $pattern;
        }
    }

}
