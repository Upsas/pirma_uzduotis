<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Pattern;
use App\Pattern\PatternReader;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass App\Pattern\PatternReader
 */
class PatternReaderTest extends TestCase
{
    protected function setUp(): void
    {
        $this->patternReaderClass = new PatternReader();
        $this->file = './src/Assets/test.txt';
    }
    /**
     * @covers ::checkIfFileExists()
     * @covers ::__construct
     */
    public function testIfFileExists()
    {
        $file = file($this->file);
        $checkIfFileExists = $this->patternReaderClass->checkIfFileExists($this->file);
        $this->assertSame($file, $checkIfFileExists);
    }
    /**
     * @covers ::getPatterns()
     * @covers ::__construct
     * @covers ::checkIfFileExists()
     * @covers App\Pattern\Pattern::__construct()
     */
    public function testGetPatterns()
    {
        foreach (file($this->file) as $patternString) {
            $patterns[] = new Pattern($patternString);
        }
        $patternsFromFile = $this->patternReaderClass->getPatterns($this->file);
        $this->assertEquals($patternsFromFile, $patterns);
    }
}
