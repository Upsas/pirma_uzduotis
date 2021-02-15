<?php
declare(strict_types = 1);
namespace Pattern;

use Pattern\Pattern;

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

    protected function checkIfFileExists(string $file): array
    {
        if (file_exists($file)) {
            return file($file);
        } else {
            return file($this->file);
        }
    }

    /**
     * @param  string $file
     * @return Pattern[]
     */
    
    public function getPatterns(string $file): array
    {
        $patterns = [];
        $patternStrings = $this->checkIfFileExists($file);
        foreach ($patternStrings as $patternString) {
            $patterns[] = new Pattern($patternString);
        }
        return $patterns;
    }
}
