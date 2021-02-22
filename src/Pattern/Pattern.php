<?php

declare(strict_types=1);

namespace App\Pattern;

class Pattern
{
    /**
     * A list of numbers
     *
     * @var string $pattern
     */

    private string $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @return string $this->pattern
     */

    public function getPattern(): string
    {
        return $this->pattern;
    }
    /**
     * Strip all numbers from pattern
     *
     * @param string $pattern
     * @return string $pattern
     */

    public function stripNumbers(): string
    {
        return  preg_replace('/[0-9]+/', '', $this->getPattern());
    }
}
