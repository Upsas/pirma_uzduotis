<?php

declare(strict_types=1);

namespace App\Factories;

use App\Pattern\Hyphenator;
use App\Pattern\Pattern;
use App\Pattern\PatternReader;

class PatternFactory
{
    public function createPatternClass(string $pattern): Pattern
    {
        return new Pattern($pattern);
    }

    public function createPatternReaderClass(): PatternReader
    {
        return new PatternReader();
    }

    public function createHyphenatorClass(array $patterns): Hyphenator
    {
        return new Hyphenator($patterns);
    }
}
