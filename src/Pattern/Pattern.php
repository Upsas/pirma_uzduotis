<?php

namespace Pattern;

class Pattern
{
    /**
     * A list of numbers
     *
     * @var array
     */

    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function getPattern()
    {
        return $this->pattern;
    }
    /**
     * Strip all numbers from pattern
     *
     * @param array $pattern
     * @return array $pattern
     */

    public function stripNumbers($pattern)
    {
        return preg_replace('/[0-9]+/', '', $pattern);
    }

}
