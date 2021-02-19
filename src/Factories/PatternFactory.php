<?php

declare(strict_types=1);

namespace App\Factories;

use App\Pattern\Hyphenator;
use App\Pattern\PatternReader;

class PatternFactory
{
    public function createPatternReaderClass(): object
    {
        return new PatternReader();
    }

    public function createHyphenatorClass(array $patterns): object
    {
        return new Hyphenator($patterns);
    }
}
