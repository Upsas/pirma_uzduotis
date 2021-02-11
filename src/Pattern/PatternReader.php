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

    public function __construct(Log $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Checks if file exists
     *
     * @param $this->file
     * @return array $this->file
     */

    // protected function checkIfFileExists()
    // {
    //     if (file_exists($this->file)) {
    //         return file($this->file);
    //     } else {
    //         return false;
    //     }
    // }

    /**
     * Get specific patterns using word
     *
     * @param string $word
     * @param array $file
     * @return array $pattern
     */

    public function getSelectedPatterns($word, $patterns)
    {
        if (empty($patterns)) {
            $patterns = file($this->file);
        }
        foreach ($patterns as $value) {

            $needle = preg_replace('/[0-9\s.]+/', '', $value);
            $value = trim($value);

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
