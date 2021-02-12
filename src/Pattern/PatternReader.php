<?php

namespace Pattern;

use Pattern\Pattern;

class PatternReader
{
    /**
     * File with all patterns
     *
     * @var url
     */

    protected $file = "./Assets/tex-hyphenation-patterns.txt";

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

    public function getPatterns()
    {
        $patterns = [];
        $patternStrings = $this->checkIfFileExists();
        foreach ($patternStrings as $patternString) {
            $patterns[] = new Pattern($patternString);
        }
        return $patterns;
    }
}
