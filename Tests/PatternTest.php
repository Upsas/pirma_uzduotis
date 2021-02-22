<?php

declare(strict_types=1);

namespace App\Tests;

use App\Pattern\Pattern;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass App\Pattern\Pattern
 */
class PatternTest extends TestCase
{
    protected function setUp(): void
    {
        $this->patternClass = new Pattern('string');
    }

    /**
     * @covers ::stripNumbers()
     * @covers ::__construct()
     * @covers ::getPattern()
     */
    
    public function testStripNumbers()
    {
        $this->assertSame('string', $this->patternClass->stripNumbers('string2'));
    }
}
