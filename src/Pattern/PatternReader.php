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

    protected function checkIfFileExists($file)
    {
        if (file_exists($file)) {
            return file($file);
        } else {
            return file($this->file);
        }
    }

    public function getPatterns($file)
    {
        $patterns = [];
        $patternStrings = $this->checkIfFileExists($file);
        foreach ($patternStrings as $patternString) {
            $patterns[] = new Pattern($patternString);
        }
        return $patterns;
    }
}
