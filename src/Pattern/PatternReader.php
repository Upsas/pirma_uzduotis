<?php

namespace Pattern;

use Database\DatabaseConnection;
use Database\Repository;
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

    public function __construct($word, Log $logger, Repository $repository)
    {
        $this->word = $word;
        $this->logger = $logger;
        $this->repository = $repository;

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

    public function getPatterns($source)
    {
        $word = $this->word;
        if ($source === 'db') {
            $values = $this->repository->getPatternsFromDb();
        } else {
            $values = $this->getDataFromFile();
        }

        foreach ($values as $value) {

            if ($source === 'db') {
                $needle = preg_replace('/[0-9\s.]+/', '', $value['patterns']);
                $value = trim($value['patterns']);
            } else {
                $needle = preg_replace('/[0-9\s.]+/', '', $value);
                $value = trim($value);
            }

            if (preg_match('/^' . $needle . '/', $word) && preg_match('/^\./', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '$/', $word) && preg_match('/\.$/', $value)) {
                $pattern[] = $value;
            } else if (preg_match('/' . $needle . '/', $word) && !preg_match('/\./', $value)) {
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
