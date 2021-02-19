<?php

declare(strict_types=1);

namespace App\Pattern;

use App\Pattern\Pattern;

class PatternReader
{
    /**
     * Local file with all patterns
     *
     * @var string $file
     */

    protected string $file = "./Assets/tex-hyphenation-patterns.txt";

    /**
     * Checks if file exists
     *
     * @param string $this->file
     * @return string[]
     */

    public function checkIfFileExists(string $file): array
    {
        return file_exists($file) ? file($file) : file($this->file);
    }

    /**
     * @param  string $file
     * @return Pattern[]
     */
    
    public function getPatterns(string $file): array
    {
        foreach ($this->checkIfFileExists($file) as $patternString) {
            $patterns[] = new Pattern($patternString);
        }
        return $patterns;
    }
}
