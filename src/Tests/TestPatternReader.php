<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Pattern;
use App\Pattern\PatternReader;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass App\Pattern\PatternReader
 */
class TestPatternReader extends TestCase
{
        
    /**
     * @covers ::checkIfFileExists()
     *
     */
    public function testIfFileExists()
    {
        $file = './Assets/test.txt';

        // Praeina bet nera coverage
        // $stub = $this->createStub(PatternReader::class);
        // $stub->method('checkIfFileExists')->with($file)->willReturn(file($file));
        // $this->assertSame(file($file), $patternReader->checkIfFileExists($file));

        // praeina su coverage
        $patternReader = new PatternReader();
        $this->assertSame(file($file), $patternReader->checkIfFileExists($file));
    }
    /**
     * @covers ::getPatterns()
     *
     */
    // Veliau paziureti
    // public function testGetPatterns()
    // {
    // }
}
